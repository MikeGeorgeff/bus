# Changelog

All notable changes to `georgeff/bus` are documented here.

---

## [1.0.1] — 2026-10-02

### Fixed
- `MiddlewareAwareDispatcher` now dispatches the command passed to `$next` to the handler. Previously the innermost step of the pipeline ignored its argument and always dispatched the command originally passed to `dispatch()`, so a middleware calling `$next($otherCommand)` had its command silently dropped before reaching the handler, contradicting the documented `$next($command)` contract. Middleware between stages already received the command passed to `$next`; the handler is now consistent with them. Middleware that calls `$next()` without an argument, which was never the documented contract, now throws `ArgumentCountError` and must pass the command

---

## [1.0.0] — 2026-02-11

Initial release.
