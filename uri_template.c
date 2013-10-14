/*
  +----------------------------------------------------------------------+
  | See LICENSE file for further copyright information                   |
  +----------------------------------------------------------------------+
  | Authors: Ioseb Dzmanashvili <ioseb.dzmanashvili@gmail.com>           |
  +----------------------------------------------------------------------+
*/

#include "php_uri_template.h"

/* {{{ proto mixed uri_template(constant template, array variables [, mixed result])
 * Returns expanded URI template string.
 */
PHP_FUNCTION(uri_template)
{
	char *tpl;
	int   len;
	zval *vars;
	zval *result = NULL;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "sa|z", 
			&tpl, &len, &vars, &result) == FAILURE) {
		RETURN_NULL();
	}
	
	if (result != NULL) {
		zval_dtor(result);
		array_init(result);
	}

	uri_template_parse(tpl, return_value, vars, result);
}
/* }}} */

/* {{{ arginfo */
ZEND_BEGIN_ARG_INFO_EX(uri_template_arg_info, 0, 3, 2)
	ZEND_ARG_INFO(0, "template")
	ZEND_ARG_ARRAY_INFO(0, "variables", 0)
	ZEND_ARG_INFO(1, "result")
ZEND_END_ARG_INFO()
/* }}} */

/* {{{ PHP_MINFO_FUNCTION
 */
PHP_MINFO_FUNCTION(uri_template)
{
	php_info_print_table_start();
	php_info_print_table_header(2, "uri_template support", "enabled");
	php_info_print_table_end();
}
/* }}} */

/* {{{ PHP_MINIT_FUNCTION
 */
PHP_MINIT_FUNCTION(uri_template)
{
	REGISTER_LONG_CONSTANT("URI_TEMPLATE_ERROR_NONE",             URI_TEMPLATE_ERROR_NONE, CONST_CS | CONST_PERSISTENT);
	REGISTER_LONG_CONSTANT("URI_TEMPLATE_ERROR",                       URI_TEMPLATE_ERROR, CONST_CS | CONST_PERSISTENT);
	REGISTER_LONG_CONSTANT("URI_TEMPLATE_ERROR_SYNTAX",         URI_TEMPLATE_ERROR_SYNTAX, CONST_CS | CONST_PERSISTENT);
	REGISTER_LONG_CONSTANT("URI_TEMPLATE_ERROR_EXPRESSION", URI_TEMPLATE_ERROR_EXPRESSION, CONST_CS | CONST_PERSISTENT);

	return SUCCESS;
}

/* {{{ uri_template_functions[]
 */
const zend_function_entry uri_template_functions[] = {
	PHP_FE(uri_template, uri_template_arg_info)
	{NULL, NULL, NULL}
};
/* }}} */

/* {{{ uri_template_module_entry
 */
zend_module_entry uri_template_module_entry = {
#if ZEND_MODULE_API_NO >= 20010901
	STANDARD_MODULE_HEADER,
#endif
	PHP_URI_TEMPLATE_EXTNAME,
	uri_template_functions,
	PHP_MINIT(uri_template),
	NULL,
	NULL,
	NULL,
	PHP_MINFO(uri_template),
#if ZEND_MODULE_API_NO >= 20010901
	PHP_URI_TEMPLATE_VERSION,
#endif
	STANDARD_MODULE_PROPERTIES
};
/* }}} */

#ifdef COMPILE_DL_URI_TEMPLATE
ZEND_GET_MODULE(uri_template)
#endif
