# ImpulseX 1.0.0

**ImpulseX** is a PocketMine-MP plugin that gives server administrators the ability to configure and customize **knockback mechanics** in-game using an intuitive form interface. With permission-based access and full FormAPI integration, this plugin helps PvP-oriented servers tailor the knockback behavior to best fit their gameplay style.

---

## 🧩 Features

* ⚙️ **Customizable Knockback**

  * Set **horizontal** and **vertical** knockback values using a simple form.
  * Values are persisted via configuration for consistent gameplay.

* 🧾 **FormAPI Integration**

  * Provides a user-friendly **Form** for in-game editing.
  * Requires the [FormAPI](https://github.com/jojoe77777/FormAPI) plugin.

* 🔄 **Dynamic Reloading**

  * Reload knockback configuration without restarting the server.

* 🔐 **Permission-Based Command System**

  * Limit command access to specific user roles.

---

## 📦 Installation

1. **Download** the latest release of `ImpulseX.phar`.
2. **Place** it in your `plugins/` directory.
3. Ensure that **FormAPI** is installed and enabled.
4. **Start** or **restart** your server.

---

## 🛠 Configuration

Upon first load, a default `config.yml` is generated:

```yaml
horizontal-knockback: 0.4
vertical-knockback: 0.35
```

You can modify these values in-game via `/impulsekbsettings` or directly from the file.

---

## 📋 Commands

| Command              | Description                            | Permission         |
| -------------------- | -------------------------------------- | ------------------ |
| `/impulsehelp`       | Displays plugin help info              | `impulse.help`     |
| `/impulsekbreload`   | Reloads the knockback configuration    | `impulse.reload`   |
| `/impulsekbsettings` | Opens the knockback configuration form | `impulse.settings` |

---

## 🛡 Permissions

| Permission         | Description                                  |
| ------------------ | -------------------------------------------- |
| `impulse.help`     | Allows access to the `/impulsehelp` command  |
| `impulse.reload`   | Allows access to reload the config           |
| `impulse.settings` | Allows usage of the knockback form interface |

---

## 🧠 Usage

1. Type `/impulsekbsettings` in-game to open the knockback form.
2. Adjust the **horizontal** and **vertical** values.
3. Save to apply them immediately.
4. Use `/impulsekbreload` if changes were made directly in the config.

> ⚠️ Note: **Vertical knockback** values above `0.4` are automatically clamped to ensure server stability.

---

## 🔧 Dependencies

* [FormAPI by jojoe77777](https://github.com/jojoe77777/FormAPI)

---

## 👨‍💻 Developer Notes

* All knockback changes are applied using the `EntityDamageByEntityEvent` handler.
* Horizontal knockback is directional based on the attacker’s position.
* Vertical values must stay under 0.4 to avoid compatibility issues with PocketMine's motion system.
* (Knockback applies to mobs aswell)

---

## 🧾 License

This plugin is open-source. Feel free to modify and distribute under your preferred terms. Contributions are welcome!

---

## 💬 Support

For questions, issues, or suggestions, open an issue or start a discussion on the GitHub repository.

---
