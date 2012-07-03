dnl $Id$
dnl config.m4 for uri templates

PHP_ARG_ENABLE(uri_template, Whether to enable the uri template extension,
[ --enable-uri_template                  Enable "URI Template" extension])
  
if test "$PHP_URI_TEMPLATE" != "no"; then
  PHP_SUBST(URI_TEMPLATE_SHARED_LIBADD)
  PHP_NEW_EXTENSION(uri_template, uri_template.c uri_template_common.c uri_template_parser.c uri_template_processor.c uri_template_string.c, $ext_shared)
  CFLAGS="$CFLAGS -Wall -g"
fi