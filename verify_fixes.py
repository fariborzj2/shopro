from playwright.sync_api import sync_playwright

def verify_fixes():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context()
        page = context.new_page()

        # Mock login if needed (assuming access to admin requires auth)
        # Since I can't easily auth, I'll verify the existence of elements on public or try to hit the page
        # If redirected to login, I'll know the server is up.

        # Test Tags Page Structure
        # This requires auth. I'll assume the server is running and I can check the file content via read_file
        # or rely on the code changes I made.

        # Since I cannot bypass auth easily in this environment without seeding a user and logging in via script,
        # I will perform a static analysis check via python script that reads the files? No, I should try to login.

        # Attempt Login
        page.goto("http://localhost:8004/admin/login")
        page.fill('input[name="username"]', "admin")
        page.fill('input[name="password"]', "password")
        page.click('button[type="submit"]')

        # Check Tags Page Header
        page.goto("http://localhost:8004/admin/blog/tags")
        page.wait_for_selector("h1")
        page.screenshot(path="tags_page.png")

        # Check Page Create Form Datepicker
        page.goto("http://localhost:8004/admin/pages/create")
        page.wait_for_selector("#published_at")
        page.screenshot(path="pages_create.png")

        browser.close()

if __name__ == "__main__":
    verify_fixes()
