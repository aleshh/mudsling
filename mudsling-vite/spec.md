# Mudsling Software Specification (Legacy PHP/Laravel)

## Product Summary
Mudsling is a single‑user alcohol intake tracker. Authenticated users define beverages, log when they drink, and review their daily history against an optional maximum intake goal. The UI is intentionally minimal, “ugly on purpose,” and optimized for mobile with a fixed nav and a persistent daily consumption bar.

## Primary Goals
- Make it fast to log a drink in one tap.
- Show daily alcohol consumption totals relative to a user‑defined max.
- Preserve beverage definitions for quick reuse.
- Provide a quick visual summary of today’s status from any screen.

## Users & Permissions
- Authenticated users only. All data is scoped to the signed‑in user.
- Anonymous users see a landing page with app description and auth links.

## Core Features
### Authentication
- Register with name, email, password, confirm password.
- Login with email + password (remember me supported in UI).
- Forgot password flow sends reset email.
- Password reset form accepts token + new password.

### Beverage Management
- CRUD for beverages (name, category, size, strength).
- Categories: Beer, Wine, Liquor, Cocktail.
- “Delete” behavior:
  - If beverage has servings, mark as deleted (soft delete).
  - If no servings exist, remove entirely.
- Beverage detail view lists servings for that beverage.

### Drink Logging
- “Drink!” page lists beverages as large tap targets.
- Tapping a beverage logs a serving with local time.
- “Add a new beverage” shortcut.
- Create beverage form includes:
  - “Save and Drink one!”
  - “Just save for later”

### History & Analytics
- History grouped by day (Today, Yesterday, weekday, or date).
- Daily totals:
  - Number of drinks
  - Total alcohol ounces
  - Percent of maximum goal
- Visual progress bar per day with red overflow beyond 100%.
- Expand/collapse each day to see individual drinks and times.
- “Undo last drink” removes the most recent serving.

### Account Settings
- Update daily maximum alcohol target (oz of alcohol).
- Quick link to manage beverages.
- Logout link.

### Persistent Daily Status Bar
- Fixed bar at bottom of screen.
- Shows today’s count and alcohol oz.
- Width represents percent of max goal.
- Colors:
  - Green background with yellow fill
  - Red fill if over max
  - Gray when no max goal set

### Miscellaneous
- About page exists but is not linked in nav.
- Local time stored using client time to calculate “day.”
- Confirmation prompts guard destructive actions (delete beverage, undo drink).
- Client sets a long‑lived cookie with local time to normalize day boundaries.

## Data Model (Legacy)
### Users
- name
- email
- password (hashed)
- maximumConsumption (daily max in oz alcohol)

### Beverages
- user_id
- name
- category
- size (oz)
- strength (% alcohol)
- deleted (boolean)

### Servings
- user_id
- beverage_id
- local_time (datetime)

## Key Screens
1. Landing / Welcome
2. Register
3. Login
4. Forgot Password
5. Reset Password
6. Drink (quick log)
7. History
8. Beverage List
9. Beverage Detail
10. Beverage Form (create/edit)
11. Account
12. About (poem)

## UX Behavior & Interaction Notes
- If no beverages exist, redirect to create form.
- Navigation is always visible, fixed at top.
- Large tap targets for drink logging.
- Confirmation prompt before deleting beverages or undoing a drink.
- Today’s status bar always visible for authenticated users.
- History days are collapsed by default, expand on tap.
- Beverage list entries show derived alcohol ounces per drink.

## Navigation & Layout
- Fixed header with a centered pint glass icon linking home.
- Authenticated nav links: Drink!, History, Account.
- Logged‑out nav links: Register, Login.
- Main content in a narrow, mobile‑first column.
- Fixed bottom consumption bar for logged‑in users.

## Visual & Brand Aesthetic
- Typography: Montserrat (body), Rakkas (headings/nav).
- Color palette: muted grays (#ccc, #eee), charcoal text (#444), blue primary buttons (#3f9fff), red warning/delete.
- Layout: narrow, mobile‑first container (max width ~400px).
- Header: fixed, uppercase navigation, central pint glass icon.
- Buttons: rounded, uppercase, bold spacing.
- Background: gray app chrome with a light "paper" main panel.
- Intentional “rough” aesthetic; minimal polish.

## Device & PWA Considerations
- Mobile web app meta tags for iOS (standalone mode, status bar color).
- Extensive apple touch icons + Android icons.
- Manifest file for installable behavior.
