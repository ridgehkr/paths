Paths
=====

An ExpressionEngine plugin to provide a few more options for getting URL segment data

The Paths plugin allows you to output information about the ExpressionEngine URL path beyond the standard segment variables.

**Usage**

```{exp:paths:full_path}```
Outputs all URL segments in order from first to last. For example, being called from http://example.com/path/to/my-page would result in /path/to/my-page

```{exp:paths:total_segments}```
Outputs the total number of segments in the URL. For example, if called from /path/to/my-page, it would output 3.

```
{exp:paths:segments}
  {segment}
{/exp:paths:segments}
```
Loops through the all segments and outputs them in order from first to last. Note that the "backspace" and "limit" parameters are available by default.
