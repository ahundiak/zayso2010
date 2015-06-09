The goal is to process a command string such as

person/edit/1

The simple router
1. peels off person,
2. finds it's associated controller
3. Calls execute with the edit/1

There are a number of possible variations on this.
The person controller might just fine another controller for edit and repeat the process
Or the controller might have methods for edit and call them directly

It's easy to type and process.

A router would
1. Check route against list of classNames, dispatch if found
2. Look for method in current class with same name, call if found
3. Give up

Absolute vs Relative Paths

