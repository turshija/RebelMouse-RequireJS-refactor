# RebelMouse-RequireJS-refactor

Script that goes through all JS files and converts them from old to new syntax

Old syntax:
```
define(['jquery', 'backbone', 'underscore', 'hogan', 'widgets/views/selector', 'hgn!widgets/templates/river/sidebar'],
  function ($, BB, _, Hogan, SelectorView, template) {
```

New syntax:
```
define(function (require) {
  var $ = require('jquery'),
      BB = require('backbone'),
      _ = require('underscore'),
      Hogan = require('hogan'),
      SelectorView = require('widgets/views/selector'),
      template = require('hgn!widgets/templates/river/sidebar');
```
