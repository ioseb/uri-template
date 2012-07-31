/*
  +----------------------------------------------------------------------+
  | See LICENSE file for further copyright information                   |
  +----------------------------------------------------------------------+
  | Authors: Ioseb Dzmanashvili <ioseb.dzmanashvili@gmail.com>           |
  +----------------------------------------------------------------------+
*/

#include "php_uri_template.h"

uri_template_vars *uri_template_vars_create()
{
	return ecalloc(1, sizeof(uri_template_vars));
}

void uri_template_vars_free(uri_template_vars *list)
{
	uri_template_var *first = list->first;
	uri_template_var *prev;
	
	while (first != NULL) {
		prev = first;
		first = first->next;
		uri_template_var_free(prev);
	}
	
	efree(list);
}

uri_template_var *uri_template_var_create()
{
	return ecalloc(1, sizeof(uri_template_var));
}

void uri_template_var_free(uri_template_var *var)
{
	efree(var->name);
	efree(var);
}

uri_template_expr *uri_template_expr_create(char operator)
{
	uri_template_expr *expr = ecalloc(1, sizeof(uri_template_expr));

	expr->op   = operator;
	expr->sep  = ',';
	expr->vars = uri_template_vars_create();
	
	switch(operator) {
		case '+':
			expr->allow = 1;
			break;
		case '.':
		case '/':
		case ';':
			expr->first = operator;
			expr->sep   = operator;
			expr->named = operator == ';';
			break;
		case '?':
		case '&':
			expr->first = operator;
			expr->sep   = '&';
			expr->named = 1;
			expr->ifemp = '=';
			break;
		case '#':
			expr->first = operator;
			expr->allow = 1;
			break;
	}
	
	return expr;
}

void uri_template_expr_add_var(uri_template_expr *expr, uri_template_var *var)
{
	uri_template_vars *list = expr->vars;
	
	if (list->first == NULL) {
		list->first = var;
		list->last = var;
	} else {
		list->last->next = var;
		list->last = var;
	}
	
	list->count++;
}

void uri_template_expr_free(uri_template_expr *expr)
{
	uri_template_vars_free(expr->vars);
	efree(expr);
}
