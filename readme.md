# Guesthouse Feedback Module – Integration Guide

This module adds a **Guest Feedback feature** to the existing Guesthouse Booking
system in the Student Portal.

The implementation is provided using **PHP placeholders** because access to the
main portal codebase and database was not available during development.
All integration points are clearly documented below.

---

## 1. Purpose & Scope

- Collect structured feedback from guests **after stay completion**
- Enforce **one feedback per booking**
- Keep the module **non-invasive** to existing booking logic
- Designed for **PHP + MySQL**

---

## 2. Business Rules (IMPORTANT)

### Feedback Eligibility
Feedback must be allowed **only after the guest’s stay is complete**.

A stay is considered complete if **either**:
- Booking status is one of:
  - `COMPLETED`
  - `CHECKED_OUT`
  - `CLOSED`
- **OR** the `departure_date` is earlier than the current date

This rule is enforced **at the booking list UI level**, not in the feedback form.

---

## 3. Files Provided

| File | Description |
|----|----|
| `feedback_button_placeholder.php` | Example logic to render the “Give Feedback” button |
| `feedback_form.php` | Feedback input form |
| `feedback_submit.php` | Feedback validation & storage logic |
| `feedback_admin_list.php` | Admin view to list submitted feedback |
| `feedback_schema.sql` | MySQL schema for feedback table |

> ⚠️ Placeholder storage uses a local JSON file.
> Replace this with database operations during integration.

---

## 4. Database Setup

### Create Feedback Table

Execute the following SQL:

```sql
CREATE TABLE guest_feedback (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  application_id BIGINT NOT NULL,
  rating_overall TINYINT NOT NULL,
  rating_cleanliness TINYINT NULL,
  rating_staff TINYINT NULL,
  comments TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_feedback_application (application_id)
);

## 5. UI Integration (Booking List Page)

Add a **“Give Feedback”** button in the booking list table.

### Rendering Logic

Render the button **only if the stay is completed**:

```php
<a href="feedback_form.php?app_id=<APPLICATION_ID>">
  Give Feedback
</a>
```

Refer to `feedback_button_placeholder.php` for the full eligibility logic.

---

## 6. Feedback Form (`feedback_form.php`)

* Accepts `app_id` via query parameter
* Collects:

  * Overall rating (required)
  * Cleanliness rating (optional)
  * Staff rating (optional)
  * Comments
* Assumes eligibility has already been checked upstream

---

## 7. Feedback Submission (`feedback_submit.php`)

### Current (Placeholder Mode)

* Validates input
* Enforces one feedback per `application_id`
* Stores feedback in:

  ```
  feedback_storage.json
  ```

### Replace with DB Insert

Replace JSON logic with a prepared PDO insert:

```sql
INSERT INTO guest_feedback
(application_id, rating_overall, rating_cleanliness, rating_staff, comments)
VALUES (?, ?, ?, ?, ?);
```

---

## 8. Admin View (`feedback_admin_list.php`)

* Lists all submitted feedback
* Supports filtering by `application_id`
* Currently reads from `feedback_storage.json`

### Replace with DB Query

```sql
SELECT
  application_id,
  rating_overall,
  rating_cleanliness,
  rating_staff,
  comments,
  created_at
FROM guest_feedback
ORDER BY created_at DESC;
```

---

## 9. Security & Validation Guidelines

* Use **prepared statements only**
* Sanitize all output (`htmlspecialchars`)
* Do not allow feedback before checkout
* Enforce one feedback per booking using DB constraint

---

## 10. Summary

This feedback module:

* Respects real-world guesthouse workflows
* Enforces correct business logic
* Is easy to integrate without modifying core booking code
* Uses placeholders to ensure clean handoff

For any questions, refer to inline comments in the PHP files.
