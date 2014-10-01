#ifndef _TABLE_H
#define _TABLE_H

#include "vector.h"

typedef struct {
  Vector keys;
  Vector values;
} TableRecord;

typedef TableRecord *Table;

Table Table_create();
void Table_put(Table t, void *key, void *value);
void Table_puts(Table t, char *key, void *value);
void* Table_get(Table t, void *key);
char* Table_gets(Table t, char *key);
void* Table_remove(Table t, void *key);
void* Table_removes(Table t, char *key);
int Table_pairs(Table t);
void Table_free(Table t);
void* Table_getKey(Table t, int idx);
void* Table_getValue(Table t, int idx);

#endif
