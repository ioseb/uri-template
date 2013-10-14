/*
  +----------------------------------------------------------------------+
  | See LICENSE file for further copyright information                   |
  +----------------------------------------------------------------------+
  | Authors: Ioseb Dzmanashvili <ioseb.dzmanashvili@gmail.com>           |
  +----------------------------------------------------------------------+
*/

#ifndef PHP_URI_TEMPLATE_H
#define PHP_URI_TEMPLATE_H

#define PHP_URI_TEMPLATE_EXTNAME "uri_template"
#define PHP_URI_TEMPLATE_VERSION "1.0"

#define URI_TEMPLATE_ERROR_NONE       0
#define URI_TEMPLATE_ERROR            1
#define URI_TEMPLATE_ERROR_SYNTAX     2
#define URI_TEMPLATE_ERROR_EXPRESSION 3

#define URI_TEMPLATE_ALLOW_UNRESERVED 0
#define URI_TEMPLATE_ALLOW_LITERALS   1
#define URI_TEMPLATE_ALLOW_RESERVED   2

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "SAPI.h"
#include "zend_API.h"
#include "php.h"
#include "php_ini.h"
#include "ext/standard/info.h"
#include "ext/standard/php_string.h"
#include "ext/standard/php_smart_str.h"
#include "ext/standard/html.h"
#include "php_variables.h"

extern zend_module_entry uri_template_module_entry;
#define phpext_uri_template_ptr &uri_template_module_entry;

#ifdef ZTS
#include "TSRM.h"
#endif

PHP_FUNCTION(uri_template);

typedef struct uri_template_var {
	struct uri_template_var *next;
	char *name;
	int length;
	int explode;
} uri_template_var;

typedef struct uri_template_vars {
	uri_template_var *first;
	uri_template_var *last;
	int count;
} uri_template_vars;

typedef struct uri_template_expr {
	char op;                   /* operator */
	char first;                /* result prefix */
	char sep;                  /* variable separator */
	char ifemp;                /* if value is empty */
	int  allow;                /* allow reserved chars */
	int  named;                /* var is named */
	int  error;                /* expression is malformed */
	uri_template_vars *vars;   /* list of expression vars */
} uri_template_expr;

uri_template_vars *uri_template_vars_create();
uri_template_var  *uri_template_var_create();
uri_template_expr *uri_template_expr_create(char operator);

void uri_template_vars_free(uri_template_vars *list);
void uri_template_var_free(uri_template_var *var);
void uri_template_expr_add_var(uri_template_expr *expr, uri_template_var *var);
void uri_template_expr_free(uri_template_expr *expr);
void uri_template_parse(char *tpl, zval *return_value, zval *vars, zval *capture);
void uri_template_process(uri_template_expr *expr, zval *vars, smart_str *result);
void uri_template_substr_copy(smart_str *dest, char *source, size_t num, int allowed_chars);

#ifdef ZTS
#define IF_G(v) TSRMG(filter_globals_id, zend_filter_globals *, v)
#else
#define IF_G(v) (filter_globals.v)

#endif

#endif