```
POST /api/create.php
```

## Params
- name (Container name, [Restriction](#Name-Restriction))
- port 
- eula (must be `TRUE`)
- rconport (optional)
- volume (ignored)
- [env-*](#Environment-Variable)

### Name Restriction
Name only can use `a-z`, `0-9` and `_`.

### Environment Variable
It can specify environment variable like `MEMORY`.