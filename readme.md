# Mudsling

This is mostly a toy app I'm building to learn Laravel. Basic features:

* Create _beverages_ and add add _servings_ of them to track consumption of alcohol
* Set a daily maximum target and display graphically and numerially where you are relative to the target
* Edit and delete beverages; they're only flagged as deleted if they have been consumed.

Uses Laravel's default Auth scaffolding. I've made almost no attempt at visual "design" in lieu of plowing ahead with features.

## Todo

### Small
* switch to using named routes
* Group routes so I don't need to repeat ->middleware('auth') for each of them
* if no maximum daily consumption, make status bar gray

### Bigger
* Update Today view to show graphs for each day
* Streak tracking
