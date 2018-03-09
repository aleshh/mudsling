# Mudsling

This is mostly a toy app I'm building to learn Laravel. Basic features:

* Create _beverages_ and add add _servings_ of them to track consumption of alcohol
* Set a daily maximum target and display graphically and numerially where you are relative to the target
* Edit and delete beverages; they're only flagged as deleted if they have been consumed.

Uses Laravel's default Auth scaffolding. I've made almost no attempt at visual "design" in lieu of plowing ahead with features.

A not-up-to-date running version is at [https://mudsling.alesh.com](https://mudsling.alesh.com).

## Todo

### Small
* Localize time
* Improve account screen
* beverage delete confirmation

### Bigger
* Select a color for each beverage
* Update Today view to show graphs for each day
* Streak tracking
* Some sort of Untapped integration
* Let a user make their consumption public