from playwright.sync_api import sync_playwright
import time

def verify_sidebar():
    with sync_playwright() as p:
        # Launch browser
        browser = p.chromium.launch(headless=True)
        context = browser.new_context()
        page = context.new_page()

        try:
            # Attempt to go to the dashboard (assuming server is at 8000)
            # Note: We probably can't login because we can't run the server properly.
            # But we can try to see if we get a response.
            page.goto("http://localhost:8000/admin/login", timeout=5000)

            # If we reach here, server is running.
            # Login as super admin (assuming migration worked, which it didn't via CLI)
            # So this is likely futile without a running environment.

            # Fill login
            page.fill('input[name="username"]', 'superadmin')
            page.fill('input[name="password"]', 'password')
            page.click('button[type="submit"]')

            page.wait_for_timeout(1000)

            # Screenshot sidebar
            page.screenshot(path="/home/jules/verification/sidebar_check.png")
            print("Screenshot taken.")

        except Exception as e:
            print(f"Verification failed: {e}")
        finally:
            browser.close()

if __name__ == "__main__":
    verify_sidebar()
