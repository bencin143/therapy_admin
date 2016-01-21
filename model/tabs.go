package model

import (
"encoding/json"
"io"
)

type Tabs []Tab

func (o Tabs) Etag() string {
if len(o) > 0 {
// the first in the list is always the most current
return Etag(o[0].CreateAt)
} else {
return ""
}
}

func (o Tabs) ToJson() string {
if b, err := json.Marshal(o); err != nil {
return "[]"
} else {
return string(b)
}
}

func TabsFromJson(data io.Reader) Tabs {
decoder := json.NewDecoder(data)
var o Tabs
err := decoder.Decode(&o)
if err == nil {
return o
} else {
return nil
}
}