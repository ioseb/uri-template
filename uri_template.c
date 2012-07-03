#include "php_uri_template.h"

PHP_FUNCTION(uri_template)
{
  char *tpl;
  int   len;
  zval *vars;
  zend_bool capture = 0;
  
  if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "sa|b", 
        &tpl, &len, &vars, &capture) == FAILURE) {
    RETURN_NULL();
  }

  array_init(return_value);
  uri_template_parse(tpl, return_value, vars, capture);
}

ZEND_BEGIN_ARG_INFO_EX(uri_template_arg_info, 0, 3, 2)
  ZEND_ARG_INFO(0, "tpl")
  ZEND_ARG_ARRAY_INFO(0, "vars", 0)
  ZEND_ARG_INFO(0, "capture")
ZEND_END_ARG_INFO()

const zend_function_entry uri_template_functions[] = {
  PHP_FE(uri_template, uri_template_arg_info)
  {NULL, NULL, NULL}
};

zend_module_entry uri_template_module_entry = {
#if ZEND_MODULE_API_NO >= 20010901
    STANDARD_MODULE_HEADER,
#endif
    PHP_URI_TEMPLATE_EXTNAME,
    uri_template_functions,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
#if ZEND_MODULE_API_NO >= 20010901
    PHP_URI_TEMPLATE_EXTVER,
#endif
    STANDARD_MODULE_PROPERTIES
};

#ifdef COMPILE_DL_URI_TEMPLATE
ZEND_GET_MODULE(uri_template)
#endif