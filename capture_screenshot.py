
from playwright.sync_api import sync_playwright, expect
import time

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    try:
        # 1. Login
        print("Navigating to login...")
        page.goto("http://localhost:8000/admin/login", timeout=30000)

        page.fill("input[name='username']", "admin")
        page.fill("input[name='password']", "password")
        page.click("button[type='submit']")

        # Wait for dashboard element instead of URL
        print("Waiting for dashboard...")
        page.wait_for_selector("text=داشبورد", timeout=30000)
        print("Dashboard loaded.")

        # 2. Go to Blog Create Page
        print("Navigating to blog create page...")
        page.goto("http://localhost:8000/admin/blog/posts/create", timeout=30000)

        # 3. Wait for the new image input to be visible
        print("Waiting for image input...")
        # "انتخاب فایل" is the text in the new design
        page.wait_for_selector("text=انتخاب فایل", timeout=10000)

        # Screenshot
        print("Taking screenshot...")
        page.screenshot(path="blog_image_input_redesign.png", full_page=True)
        print("Screenshot saved.")

    except Exception as e:
        print(f"Error: {e}")
        page.screenshot(path="error_state_final.png")

    finally:
        browser.close()

with sync_playwright() as playwright:
    run(playwright)
