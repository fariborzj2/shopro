from playwright.sync_api import sync_playwright
import time

def run():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(viewport={'width': 1280, 'height': 800})
        page = context.new_page()

        # 1. Switch Theme
        print("Switching theme to template-3...")
        response = page.goto("http://localhost:8000/theme.php?skin=template-3")
        # Ensure we are redirected or cookie is set.
        # theme.php usually redirects back.
        page.wait_for_load_state("networkidle")

        # 2. Go to Homepage
        print("Navigating to Homepage...")
        page.goto("http://localhost:8000/")
        page.wait_for_load_state("networkidle")

        # Small delay for animations/alpine
        time.sleep(2)

        # 3. Take Screenshot
        print("Taking screenshot...")
        page.screenshot(path="template3_home.png", full_page=True)

        browser.close()
        print("Done.")

if __name__ == "__main__":
    run()
