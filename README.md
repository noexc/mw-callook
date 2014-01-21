# MW-Callook

A mediawiki interface to the [Callook](http://callook.info) ham radio
database.

Currently only provides a few small functions, but could be expanded with more
if there was a want/need.

- `{{#callsign: t3est}}` renders the following HTML in the wiki page:

```html
Full M Name (<a href="http://callook.info/t3est">T3EST</a>)
```

- `{{#callsignlink: t3est}}` simply links to t3est on callook.

- `{{#callsignlist:t3est,t4est,t5est}}` displays the same as `callsign` above,
  but in list format and for multiple callsigns.

## License

MIT License. (c) 2014 Ricky Elrod.
