#ifndef _TSVDB_H
#define _TSVDB_H

#include "vector.h"

typedef struct
{
  Vector rows;
  int maxcolumns;

} TSVdbRecord;

typedef TSVdbRecord *TSVdb;

TSVdb TSVdb_create(char* filename);
int TSVdb_addRowsFromFile(TSVdb tsvdb, char *filename);
char* TSVdb_getCell(TSVdb tsvdb, int row, int column);
int TSVdb_rows(TSVdb tsvdb);
int TSVdb_columns(TSVdb tsvdb);
int TSVdb_swapRows(TSVdb tsvdb, int row1, int row2);
int TSVdb_deleteRow(TSVdb tsvdb, int row);

#endif
