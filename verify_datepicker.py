from playwright.sync_api import sync_playwright

def verify_blog_create_page():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context()
        page = context.new_page()

        # Mock login (requires admin session)
        # Since I cannot easily mock session without login, I will try to access the login page
        # and then manually set a cookie if I knew how, but here I'll just check if the assets load
        # on a public page or try to hit the admin page and see if it redirects to login.
        # Wait, I need to see the date picker.

        # Let's assume I can access the login page.
        page.goto("http://localhost:8003/admin/login")

        # Perform login
        page.fill('input[name="username"]', "admin")
        page.fill('input[name="password"]', "password")
        page.click('button[type="submit"]')

        # Navigate to Blog Create
        page.goto("http://localhost:8003/admin/blog/posts/create")

        # Check if date picker input exists
        page.wait_for_selector("#published_at")

        # Click to open date picker (to verify it works visually)
        page.click("#published_at")

        # Take screenshot
        page.screenshot(path="blog_create_datepicker.png")

        browser.close()

if __name__ == "__main__":
    verify_blog_create_page()
