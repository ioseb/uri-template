#include "php_uri_template.h"

#define MAX_ONE_BYTE_CHAR   0x7f
#define MAX_TWO_BYTE_CHAR   0x7ff
#define MAX_THREE_BYTE_CHAR 0xffff
#define MAX_FOUR_BYTE_CHAR  0x1fffff

static unsigned char hexchars[]       = "0123456789ABCDEF";
static unsigned char badchar[]        = {239, 191, 189};
static unsigned char urlchars[3][128] = {
  {
    0,     0,   0,   0,   0,   0,   0,   0,
    0,     0,   0,   0,   0,   0,   0,   0,
    0,     0,   0,   0,   0,   0,   0,   0,
    0,     0,   0,   0,   0,   0,   0,   0,
    0,     0,   0,   0,   0,   0,   0,   0,
    0,     0,   0,   0,   0, '-', '.',   0,
    '0', '1', '2', '3', '4', '5', '6', '7',
    '8', '9',   0,   0,   0,   0,   0,   0,
    0,   'A', 'B', 'C', 'D', 'E', 'F', 'G',
    'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
    'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W',
    'X', 'Y', 'Z',   0,   0,   0,   0, '_',
    0,   'a', 'b', 'c', 'd', 'e', 'f', 'g',
    'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o',
    'p', 'q', 'r', 's', 't', 'u', 'v', 'w',
    'x', 'y', 'z',   0,   0,   0, '~',  0
  }, {
    0,     0,   0,   0,   0,   0,   0,   0,
    0,     0,   0,   0,   0,   0,   0,   0,
    0,     0,   0,   0,   0,   0,   0,   0,
    0,     0,   0,   0,   0,   0,   0,   0,
    0,   '!',   0, '#', '$',   0, '&',   0,
    '(', ')', '*', '+', ',', '-', '.', '/',
    '0', '1', '2', '3', '4', '5', '6', '7',
    '8', '9', ':', ';',   0, '=',   0, '?',
    '@', 'A', 'B', 'C', 'D', 'E', 'F', 'G',
    'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
    'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W',
    'X', 'Y', 'Z', '[',   0, ']',   0, '_',
    0,   'a', 'b', 'c', 'd', 'e', 'f', 'g',
    'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o',
    'p', 'q', 'r', 's', 't', 'u', 'v', 'w',
    'x', 'y', 'z',   0,   0,   0, '~',  0
  }, {
    0,     0,   0,   0,   0,   0,   0,   0,
    0,     0,   0,   0,   0,   0,   0,   0,
    0,     0,   0,   0,   0,   0,   0,   0,
    0,     0,   0,   0,   0,   0,   0,   0,
    0,   '!',   0, '#', '$',   0, '&','\'',
    '(', ')', '*', '+', ',', '-', '.', '/',
    '0', '1', '2', '3', '4', '5', '6', '7',
    '8', '9', ':', ';',   0, '=',   0, '?',
    '@', 'A', 'B', 'C', 'D', 'E', 'F', 'G',
    'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
    'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W',
    'X', 'Y', 'Z', '[',   0, ']',   0, '_',
    0,   'a', 'b', 'c', 'd', 'e', 'f', 'g',
    'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o',
    'p', 'q', 'r', 's', 't', 'u', 'v', 'w',
    'x', 'y', 'z',   0,   0,   0, '~',  0
  }
};

inline static void append_encoded(smart_str *dest, const char *source, size_t num) {
  unsigned char c;
  int i;

  for (i = 0; i < num; i++) {
    c = *source++;  

    smart_str_appendc(dest, '%');
    smart_str_appendc(dest, hexchars[c >> 4]);
    smart_str_appendc(dest, hexchars[c & 15]);
  }
}

inline static void utf8_append_badchar(smart_str *dest) {
  int i;

  for (i = 0; i < 3; i++) {
    smart_str_appendc(dest, '%');
    smart_str_appendc(dest, hexchars[badchar[i] >> 4]);
    smart_str_appendc(dest, hexchars[badchar[i] & 15]);
  }
}

static char *utf8_copy_char(smart_str *dest, char **cursor) {
  unsigned char first = **cursor;
  unsigned char second = *(*cursor + 1) ^ 0x80;
  unsigned char third;
  unsigned char fourth;

  if (second & 0xC0) {
    utf8_append_badchar(dest);
    return (*cursor)++;
  }

  if (first < 0xE0) {
    if (first < 0xC0) {
      utf8_append_badchar(dest);
      return (*cursor)++;
    }

    if ((((first << 6) | second) 
        & MAX_TWO_BYTE_CHAR) <= MAX_ONE_BYTE_CHAR) {
      utf8_append_badchar(dest);
      return (*cursor)++;
    }

    append_encoded(dest, *cursor, 2);
    return (*cursor) += 2;
  }

  third = *(*cursor + 2) ^ 0x80;
  if (third & 0xC0) {
    utf8_append_badchar(dest);
    return (*cursor)++;
  }

  if (first < 0xF0) {
    if ((((((first << 6) | second) << 6) | third) 
        & MAX_THREE_BYTE_CHAR) <= MAX_TWO_BYTE_CHAR) {
      utf8_append_badchar(dest);
      return (*cursor)++;
    }

    append_encoded(dest, *cursor, 3);
    return (*cursor) += 3;
  }

  fourth = *(*cursor + 3) ^ 0x80;
  if (fourth & 0xC0) {
    utf8_append_badchar(dest);
    return (*cursor)++;
  }
  
  if (first < 0xF8) {
    if (((((((first << 6 | second) << 6) | third) << 6) | fourth)
        & MAX_FOUR_BYTE_CHAR) <= MAX_THREE_BYTE_CHAR) {
      utf8_append_badchar(dest);
      return (*cursor)++;
    }

    append_encoded(dest, *cursor, 4);
    return (*cursor) += 4;
  }
  
  return *cursor;
}

void uri_template_substr_copy(smart_str *dest, char *source, size_t num, int allowed_chars) {
  unsigned char c;
  
  if (num <= 0) {
    return;
  }

  while (*source && num-- > 0) {
    c = *source;
    
    if (c > 127) {
      utf8_copy_char(dest, &source);
    } else {
      if (urlchars[allowed_chars][c]) {
        smart_str_appendc(dest, *source);
      } else {
        append_encoded(dest, source, 1);
      }

      source++;
    }
  }
}

void uri_template_copy_var_valuel(smart_str *dest, zval *val, uri_template_expr *expr, uri_template_var *var) {
  size_t len = var->length && (var->length < Z_STRLEN_P(val)) 
    ? var->length : Z_STRLEN_P(val);
  int allowed_chars = expr->op == '+' || expr->op == '#'
    ? URI_TEMPLATE_ALLOW_RESERVED : URI_TEMPLATE_ALLOW_UNRESERVED;
  
  uri_template_substr_copy(dest, Z_STRVAL_P(val), len, allowed_chars);
}

void uri_template_copy_var_value(smart_str *dest, zval *val, uri_template_expr *expr, uri_template_var *var) {
  int allowed_chars = expr->op == '+' || expr->op == '#'
    ? URI_TEMPLATE_ALLOW_RESERVED : URI_TEMPLATE_ALLOW_UNRESERVED;
  
  uri_template_substr_copy(dest, Z_STRVAL_P(val), Z_STRLEN_P(val), allowed_chars);
}

void uri_template_copy_var_name(smart_str *dest, uri_template_var *var) {
  uri_template_substr_copy(dest, var->name, strlen(var->name), URI_TEMPLATE_ALLOW_UNRESERVED);
}