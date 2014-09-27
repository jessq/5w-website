#include "vector.h"

#ifndef _CGIPARSE_H
#define _CGIPARSE_H

typedef struct
{
  char *data;

  Vector key;
  Vector value;
} CGIparseRecord;

typedef CGIparseRecord *CGIparse;

CGIparse CGIparse_create(char *data_in);
int CGIparse_pairs(CGIparse cgip);
void CGIparse_free(CGIparse cgip);
char* CGIparse_key(CGIparse cgip, int index);
char* CGIparse_value(CGIparse cgip, int index);


#endif
