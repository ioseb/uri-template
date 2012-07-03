#include "php_uri_template.h"

#define SHIFT_BACK(var) (!var->explode ? var->length == 0 \
  ? 0 : ((int) log10(var->length) + 1) + 1 : 1)

#define IS_OPERATOR(c) c == '+' || c == '#' || c == '.' || \
  c == '/' || c == ';' || c == '?' || c == '&'

inline static int extract_num(char *str, int len) {
  char buff[len + 1];
  strncpy(buff, str, len);
  buff[len] = '\0';
  
  return atoi(buff);
}

static uri_template_expr *build_expr(char *tpl, int len) {
  uri_template_expr *expr;
  uri_template_var  *var = uri_template_var_create();
  char *name = tpl, *start = tpl, *prefix;
  
  if (IS_OPERATOR(*tpl)) {
    expr = uri_template_expr_create(*tpl++);
    name = tpl;
  } else {
    expr = uri_template_expr_create(0);
  }
  
  while (*tpl != '\0' && tpl - start <= len) {
    switch(*tpl) {
      case '%':
        if (name + len - tpl > 2) {
          if (isxdigit(*(tpl + 1))) {
            if (isxdigit(*(++tpl + 1))) {
              tpl++;
            } else {
              expr->error = URI_TEMPLATE_ERROR;
            }
          } else {
            expr->error = URI_TEMPLATE_ERROR;
          }
        } else {
          expr->error = URI_TEMPLATE_ERROR;
        }
        
        break;
      case ':':
        prefix = ++tpl;

        if (*tpl >= '1' && *tpl++ <= '9') {
          while (isdigit(*tpl)) {
            tpl++;
          }
          
          if (*tpl == ',' || *tpl == '}') {
            if (tpl - prefix < 5) {
              var->length = extract_num(tpl - (tpl - prefix), tpl - prefix);
            } else {
              expr->error = URI_TEMPLATE_ERROR;
            }
            
            tpl--;
          } else {
            expr->error = URI_TEMPLATE_ERROR;
          }
        } else {
          expr->error = URI_TEMPLATE_ERROR;
        }
        
        break;
      case '*':
        if (*(tpl + 1) == '}' || *(tpl + 1) == ',') {
          var->explode = 1;
        } else {
          expr->error = URI_TEMPLATE_ERROR;
        }
        
        break;
      case ',':
      case '}':
        var->name = estrndup(name, tpl - name - SHIFT_BACK(var));
        uri_template_expr_add_var(expr, var);
        
        if (*tpl == ',') {
          var = uri_template_var_create();
          name = tpl + 1;
        }
        
        break;
      default:
        expr->error |= !(
          isalnum(*tpl) || 
          *tpl == '_' ||
          *tpl == '.'
        );
        
        break;
    }
    
    tpl++;
  }
  
  return expr;
}

inline static void append_malformed_expr(smart_str *dest, char *tpl, int len) {
  smart_str_appendc(dest, '{');
  smart_str_appendl(dest, tpl, len);
  smart_str_appendc(dest, '}');
}

inline static void add_expr_to_array(zval *expressions, uri_template_expr *expr) {
  zval *result;
  zval *vars;
  
  MAKE_STD_ZVAL(result);
  array_init(result);
  
  char op[2] = {expr->op, 0};
  add_assoc_string(result, "op", op, 1);
  
  char sep[2] = {expr->sep, 0};
  add_assoc_string(result, "sep", sep, 1);
  
  char ifemp[2] = {expr->ifemp, 0};
  add_assoc_string(result, "ifemp", ifemp, 1);
  
  add_assoc_bool(result, "allow", expr->allow);
  add_assoc_bool(result, "named", expr->named);
  add_assoc_bool(result, "error", expr->error);
  
  MAKE_STD_ZVAL(vars);
  array_init(vars);
  
  uri_template_var *next = expr->vars->first;
  
  while (next != NULL) {
    zval *var;
    
    MAKE_STD_ZVAL(var);
    array_init(var);
    
    add_assoc_string(var, "name", next->name, 1);
    add_assoc_long(var, "length", next->length);
    add_assoc_bool(var, "explode", next->explode);
    add_next_index_zval(vars, var);
    
    next = next->next;
  }
  
  add_assoc_zval(result, "vars", vars);
  add_next_index_zval(expressions, result);
}

void uri_template_parse(char *tpl, zval *return_value, zval *vars, zend_bool capture) {
  smart_str result = {0};
  zval *expressions = NULL;
  zval vars_ptr;
  unsigned char c;
  char *start;
  int state = URI_TEMPLATE_ERROR_NONE;
  
  if (capture) {
    MAKE_STD_ZVAL(expressions);
    array_init(expressions);
  }
  
  vars_ptr = *vars;
  zval_copy_ctor(&vars_ptr);
  
  while (*tpl) {
    if (*tpl++ == '{') {
      start = tpl;

      while (*tpl) {
        if (*tpl++ == '}') {
          uri_template_expr *expr = build_expr(start, tpl - start - 1);

          if (expr->error) {
            append_malformed_expr(&result, start, tpl - start - 1);
            
            if (state == URI_TEMPLATE_ERROR_NONE) {
              state = URI_TEMPLATE_ERROR_EXPRESSION;
            }
          } else {
            uri_template_process(expr, &vars_ptr, &result);
          }
          
          if (capture) {
            add_expr_to_array(expressions, expr);
          }
          
          uri_template_expr_free(expr);
          break;
        }
      }
    } else {
      c = *(tpl - 1);
      
      if (c == '}') {
        smart_str_appendc(&result, '{');
        smart_str_appendc(&result, '}');
        state = URI_TEMPLATE_ERROR_SYNTAX;
      } else if (c == '%' && isxdigit(*tpl) && isxdigit(*(tpl + 1))) {
        smart_str_appendc(&result, '%');
        smart_str_appendc(&result, *tpl++);
        smart_str_appendc(&result, *tpl++);
      } else {
        uri_template_substr_copy(&result, tpl - 1, 1, URI_TEMPLATE_ALLOW_LITERALS);
      }
    }
  }
  
  zval_dtor(&vars_ptr);
  smart_str_0(&result);
  add_assoc_string(return_value, "result", result.c, 1);
  add_assoc_long(return_value, "state", state);
  
  if (expressions != NULL) {
    add_assoc_zval(return_value, "expressions", expressions);
  }
  
  smart_str_free(&result);
}