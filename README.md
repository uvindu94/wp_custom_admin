# wp_custom_admin

A lightweight custom admin panel for WordPress that allows users to upload and manage posts without accessing the default WordPress admin panel (`wp-admin`). This solution is ideal for low-performance servers where the default WordPress backend consumes too many resources.

---

## 🚀 Features

- Custom login system
- Create new posts
- Edit existing posts
- Delete posts
- Minimalistic and clean interface
- Low resource consumption
- Easy to deploy

---

## 📁 File Structure
wp_custom_admin/ 
                ├── delete_post.php 
                ├── edit_post.php 
                ├── index.php 
                ├── login.php 
                ├── logout.php 
                ├── post_upload.php 
                └── style.css


---

## 📦 Installation

1. Upload the `wp_custom_admin` folder to your WordPress root directory (typically `public_html`).
2. Ensure the folder and files have the appropriate permissions.
3. Visit your custom admin panel at: https://www.yourdomain.com/wp_custom_admin


---

## ⚙️ Requirements

- WordPress site installed and running
- PHP-enabled server
- User credentials managed within the custom login system (or hook into WordPress user system if needed)

---

## 🔒 Security Notes

- This is a custom-built solution. Please secure it appropriately before deploying to production.
- It's recommended to restrict access and validate users properly.

---

## 💡 Why Use This?

The default WordPress admin (`wp-admin`) can be heavy on system resources, especially for low-cost hosting plans. `wp_custom_admin` is designed to be a lightweight alternative that focuses only on essential post management features, ensuring faster performance and lower memory usage.

---

## 📬 Support

Feel free to raise an issue or submit a pull request for improvements!

---

## 📄 License

MIT License




