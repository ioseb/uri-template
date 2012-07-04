#include "php_uri_template.h"

#define URI_TEMPLATE_PROCESSING_ARGS \
  uri_template_expr *expr, uri_template_var *var, zval *vars, smart_str *result

inline static zend_bool array_is_assoc(zval *array) {
  int key_type;
  uint key_len;
  char *str_key;
  ulong num_key;
  HashPosition pos;
  zval **entry;
  
  zend_hash_internal_pointer_reset_ex(Z_ARRVAL_P(array), &pos);
  while (zend_hash_get_current_data_ex(Z_ARRVAL_P(array), (void**)&entry, &pos) == SUCCESS) {
    key_type = zend_hash_get_current_key_ex(Z_ARRVAL_P(array), &str_key, &key_len, &num_key, 0, &pos);
    
    if (key_type == HASH_KEY_IS_STRING) {
      return 1;
    }
    
    zend_hash_move_forward_ex(Z_ARRVAL_P(array), &pos);
  }
  
  return 0;
}

static void process_associative_array(URI_TEMPLATE_PROCESSING_ARGS) {
  uint key_len;
  char *str_key;
  char separator = var->explode ? expr->sep : ',';
  int i = 0;
  ulong num_key;
  HashPosition pos;
  zval **entry;
  
  for (zend_hash_internal_pointer_reset_ex(Z_ARRVAL_P(vars), &pos);
       zend_hash_has_more_elements_ex(Z_ARRVAL_P(vars), &pos) == SUCCESS;
       zend_hash_move_forward_ex(Z_ARRVAL_P(vars), &pos)) {
    
    if (zend_hash_get_current_data_ex(Z_ARRVAL_P(vars), (void**)&entry, &pos) == SUCCESS) {
      zend_hash_get_current_key_ex(Z_ARRVAL_P(vars), &str_key, &key_len, &num_key, 0, &pos);

      if (i > 0) {
        smart_str_appendc(result, separator);
      }

      convert_to_string_ex(entry);
      uri_template_substr_copy(result, str_key, key_len - 1, URI_TEMPLATE_ALLOW_UNRESERVED);

      if (var->explode) {
        if (!Z_STRLEN_PP(entry)) {
          if (expr->ifemp) {
            smart_str_appendc(result, expr->ifemp);
          }
        } else {
          smart_str_appendc(result, '=');
        }

        uri_template_copy_var_value(result, *entry, expr, var);
      } else {
        if (Z_STRLEN_PP(entry)) {
          smart_str_appendc(result, ',');
          uri_template_copy_var_value(result, *entry, expr, var);
        }
      }
    }
    
    i++;
  }
}

static void process_indexed_array(URI_TEMPLATE_PROCESSING_ARGS) {
  HashPosition pos;
  zval **entry;
  char separator = var->explode ? expr->sep : ',';
  int i = 0; 
  
  for (zend_hash_internal_pointer_reset_ex(Z_ARRVAL_P(vars), &pos);
       zend_hash_has_more_elements_ex(Z_ARRVAL_P(vars), &pos) == SUCCESS;
       zend_hash_move_forward_ex(Z_ARRVAL_P(vars), &pos)) {
    
    if (zend_hash_get_current_data_ex(Z_ARRVAL_P(vars), (void**)&entry, &pos) == SUCCESS) {
      if (i > 0) {
        smart_str_appendc(result, separator);
      }
      
      convert_to_string_ex(entry);
      
      if (var->explode && expr->named) {
        uri_template_copy_var_name(result, var);
        
        if (!Z_STRLEN_PP(entry)) {
          if (expr->ifemp) {
            smart_str_appendc(result, expr->ifemp);
          }
        } else {
          smart_str_appendc(result, '=');
        }
      }
      
      uri_template_copy_var_value(result, *entry, expr, var);
    }
    
    i++;
  }
}

static void process_var_array(URI_TEMPLATE_PROCESSING_ARGS) {
  smart_str eval = {0};
  
  if (array_is_assoc(vars)) {
    process_associative_array(expr, var, vars, &eval);
  } else {
    process_indexed_array(expr, var, vars, &eval);
  }
  
  smart_str_0(&eval);
  
  if (!var->explode) {
    if (eval.len) {
      if (expr->named) {
        uri_template_copy_var_name(result, var);
        smart_str_appendc(result, '=');
      }
      
      smart_str_appendl(result, eval.c, eval.len);
    } else {
      if (expr->named) {
        uri_template_copy_var_name(result, var);
        
        if (expr->ifemp) {
          smart_str_appendc(result, expr->ifemp);
        }
      }
    }
  } else {
    smart_str_appendl(result, eval.c, eval.len);
  }
  
  smart_str_free(&eval);
}

static zend_bool process_var(URI_TEMPLATE_PROCESSING_ARGS) {
  zval **entry;
  zend_bool found = zend_hash_find(
    Z_ARRVAL_P(vars),
    var->name,
    strlen(var->name) + 1,
    (void **)&entry
  ) == SUCCESS && Z_TYPE_PP(entry) != IS_NULL;
  
  if (found) {
    if (Z_TYPE_PP(entry) == IS_ARRAY) {
      process_var_array(expr, var, *entry, result);
      found = zend_hash_num_elements(Z_ARRVAL_PP(entry)) > 0;
    } else {
      convert_to_string_ex(entry);
      
      if (!expr->named) {
        uri_template_copy_var_value(result, *entry, expr, var);
      } else {
        uri_template_copy_var_name(result, var);
        
        if (!Z_STRLEN_PP(entry)) {
          if (expr->ifemp) {
            smart_str_appendc(result, expr->ifemp);
          }
        } else {
          smart_str_appendc(result, '=');
          uri_template_copy_var_value(result, *entry, expr, var);
        }
      }
    }
  }
  
  return found;
}

void uri_template_process(uri_template_expr *expr, zval *vars, smart_str *result) {
  uri_template_var *var = expr->vars->first;
  zend_bool processed = 0;
  zend_bool prev_step = 0;
  int i = 0;
  
  while (var != NULL) {
    smart_str eval = {0};
    
    processed = process_var(expr, var, vars, &eval);
    
    if (processed) {
      smart_str_0(&eval);
      
      if (i == 0 && expr->first) {
        smart_str_appendc(result, expr->first);
        i++;
      } else if (prev_step) {
        smart_str_appendc(result, expr->sep);
      }
      
      smart_str_appendl(result, eval.c, eval.len);
    }
    
    smart_str_free(&eval);
    prev_step |= processed;
    var = var->next;
  }
}