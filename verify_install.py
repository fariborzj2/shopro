from playwright.sync_api import sync_playwright

def verify_install_page():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()

        # Navigate to install.php on the new port
        page.goto("http://localhost:8002/install.php")

        # Wait for the page to load and the "Step 1" to be visible
        page.wait_for_selector("text=نصب سیستم مدیریت")

        # Take a screenshot of the initial state
        page.screenshot(path="install_step1.png")

        # Fill in DB details (mock)
        page.fill("input[x-model='db.host']", "localhost")
        page.fill("input[x-model='db.name']", "test_db")
        page.fill("input[x-model='db.user']", "root")

        # Take a screenshot of filled form
        page.screenshot(path="install_step1_filled.png")

        browser.close()

if __name__ == "__main__":
    verify_install_page()
