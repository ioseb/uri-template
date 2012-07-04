#include "php_uri_template.h"

PHP_FUNCTION(uri_template)
{
  char *tpl;
  int   len;
  zval *vars;
  zval *result = NULL;
  zval *ret_ptr;
  zval **string;
  
  if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "sa|z", 
        &tpl, &len, &vars, &result) == FAILURE) {
    RETURN_NULL();
  }
  
  if (result != NULL) {
    zval_dtor(result);
    array_init(result);
    ret_ptr = result;
  } else {
    MAKE_STD_ZVAL(ret_ptr);
    array_init(ret_ptr);
  }
  
  uri_template_parse(tpl, ret_ptr, vars, result != NULL);
  zend_hash_find(Z_ARRVAL_P(ret_ptr), "result", strlen("result") + 1, (void **)&string);
  
  RETVAL_STRING(Z_STRVAL_PP(string), 1);
}

ZEND_BEGIN_ARG_INFO_EX(uri_template_arg_info, 0, 3, 2)
  ZEND_ARG_INFO(0, "tpl")
  ZEND_ARG_ARRAY_INFO(0, "vars", 0)
  ZEND_ARG_INFO(1, "result")
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