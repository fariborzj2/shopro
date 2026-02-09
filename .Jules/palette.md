## 2025-02-05 - [RTL Accessibility Polish]
**Learning:** In RTL (Right-to-Left) applications, 'Skip to Content' links should be positioned appropriately (e.g., top-right) to match the visual flow and ensure keyboard focus is predictable.
**Action:** Always include a localized Skip to Content link and ensure icon-only buttons have descriptive ARIA labels in the target language (Persian).
## 2025-02-05 - [AI Modal Accessibility and Global Feedback]
**Learning:** Global UI components like AI-driven modals and toast notifications must have proper ARIA semantic roles (dialog, alert) and dynamic labeling (aria-labelledby) to be truly accessible in complex CMS environments.
**Action:** When adding modals or feedback systems, always include role="dialog"/"alert" and link inputs/textareas to their respective headers via IDs.
