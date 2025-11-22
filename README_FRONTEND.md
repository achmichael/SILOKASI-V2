# 🚀 GDSS Frontend Documentation

## Group Decision Support System - Housing Location Selection
### Metode: ANP-WP-BORDA

---

## 📋 Table of Contents
1. [Overview](#overview)
2. [Technology Stack](#technology-stack)
3. [Installation](#installation)
4. [File Structure](#file-structure)
5. [Features](#features)
6. [User Guide](#user-guide)
7. [API Integration](#api-integration)

---

## 🎯 Overview

Frontend aplikasi GDSS yang modern, responsive, dan user-friendly untuk pemilihan lokasi perumahan menggunakan metode:
- **AHP** (Analytical Hierarchy Process) - Perhitungan bobot criteria
- **ANP** (Analytical Network Process) - Interdependency analysis
- **WP** (Weighted Product) - Alternative scoring
- **BORDA** - Multi-DM aggregation

---

## 🛠 Technology Stack

| Technology | Version | Purpose |
|-----------|---------|---------|
| Laravel | 11.x | Backend framework |
| Vite | 7.x | Build tool & dev server |
| Tailwind CSS | 4.x | Styling framework |
| Alpine.js (optional) | - | Lightweight JavaScript |
| Chart.js | Latest | Data visualization |
| SweetAlert2 | Latest | Beautiful alerts |
| Axios | 1.11.x | HTTP client |

---

## 📦 Installation

### Prerequisites
```bash
# Node.js >= 18
node --version

# NPM >= 9
npm --version

# PHP >= 8.2
php --version
```

### Setup Steps
```bash
# 1. Install dependencies
npm install

# 2. Build for development
npm run dev

# 3. Build for production
npm run build

# 4. Start Laravel server
php artisan serve

# 5. Access application
# Open browser: http://localhost:8000
```

---

## 📁 File Structure

```
resources/
├── css/
│   └── app.css                 # Custom Tailwind styles
├── js/
│   ├── app.js                  # Main JS entry
│   ├── api.js                  # API service layer
│   └── bootstrap.js            # Axios setup
└── views/
    ├── layouts/
    │   └── app.blade.php       # Main layout
    ├── dashboard.blade.php     # Dashboard
    ├── criteria/
    │   └── index.blade.php     # Criteria management
    ├── alternatives/
    │   └── index.blade.php     # Alternatives management
    ├── decision-makers/
    │   └── index.blade.php     # DM management
    ├── pairwise/
    │   └── index.blade.php     # AHP matrix input
    ├── anp/
    │   └── index.blade.php     # ANP matrix input
    ├── ratings/
    │   └── index.blade.php     # Alternative ratings
    └── results/
        └── index.blade.php     # Calculation results
```

---

## ✨ Features

### 1. **Responsive Design**
- ✅ Mobile-first approach
- ✅ Tablet & desktop optimized
- ✅ Collapsible sidebar on mobile
- ✅ Touch-friendly interfaces

### 2. **Modern UI Components**
- **Cards**: Clean, shadowed containers
- **Buttons**: Primary, secondary, success, danger variants
- **Forms**: Validated inputs with error states
- **Tables**: Sortable, hoverable, responsive
- **Badges**: Status indicators
- **Alerts**: Success, error, warning, info
- **Modals**: Slide-in animations

### 3. **Dark Mode Support**
```css
/* Automatic dark mode detection */
.dark\:bg-gray-800 { ... }
.dark\:text-white { ... }
```

### 4. **Data Visualization**
- **Bar Charts**: Alternative comparisons
- **Progress Bars**: Weight distributions
- **Step Indicators**: Workflow progress

### 5. **Real-time Validation**
- Client-side form validation
- API error handling
- Success/error notifications

---

## 📖 User Guide

### Workflow Steps

#### **Step 1: Setup Data Master**

1. **Manage Criteria**
   - Navigate to: `Criteria` menu
   - Click `Add Criteria`
   - Fill form:
     - Code: `C1, C2, ...`
     - Name: `Lokasi Strategis, Harga Tanah, ...`
     - Type: `Benefit` or `Cost`
   - Save

2. **Manage Alternatives**
   - Navigate to: `Alternatives` menu
   - Click `Add Alternative`
   - Fill form:
     - Code: `A1, A2, ...`
     - Name: `Gentan, Palur, Bekonang, ...`
     - Location: (optional)
   - Save

3. **Manage Decision Makers**
   - Navigate to: `Decision Makers` menu
   - Click `Add Decision Maker`
   - Fill form:
     - Name: `DM 1, DM 2, ...`
     - Weight: `0.3, 0.5, 1.0` (decimal 0-1)
   - Save

#### **Step 2: AHP Matrix (Pairwise Comparison)**

1. Navigate to: `Step 1: AHP Matrix`
2. Input comparison values (1-9 scale):
   - `1` = Equal importance
   - `3` = Moderate importance
   - `5` = Strong importance
   - `7` = Very strong importance
   - `9` = Extreme importance
3. Click `Calculate AHP`
4. Check Consistency Ratio (CR < 0.1)

#### **Step 3: ANP Matrix (Interdependency)**

1. Navigate to: `Step 2: ANP Matrix`
2. Input interdependency values
3. Click `Calculate ANP`
4. Review ANP weights

#### **Step 4: Alternative Ratings**

1. Navigate to: `Step 3: Ratings`
2. Select Decision Maker from dropdown
3. Rate each alternative for each criterion (1-5)
4. Click `Save Ratings`
5. Repeat for all Decision Makers

#### **Step 5: View Results**

1. Navigate to: `Step 4: Results`
2. Click `Calculate All` to run full analysis
3. View results in tabs:
   - **Final Ranking**: Overall winner
   - **AHP Results**: Criteria weights
   - **ANP Results**: Adjusted weights
   - **WP Results**: Alternative scores
   - **BORDA Results**: Aggregated rankings

---

## 🔌 API Integration

### Service Layer (`resources/js/api.js`)

All API calls are centralized in `api.js`:

```javascript
import { criteriaAPI } from './api.js';

// Get all criteria
const response = await criteriaAPI.getAll();

// Create new criteria
await criteriaAPI.create({
    code: 'C1',
    name: 'Lokasi Strategis',
    type: 'benefit'
});

// Update criteria
await criteriaAPI.update(id, data);

// Delete criteria
await criteriaAPI.delete(id);
```

### Available APIs

| API Module | Methods |
|-----------|---------|
| `criteriaAPI` | getAll, getById, create, update, delete |
| `alternativeAPI` | getAll, getById, create, update, delete |
| `decisionMakerAPI` | getAll, getById, create, update, delete |
| `pairwiseAPI` | getMatrix, storeMatrix |
| `anpAPI` | getMatrix, storeMatrix |
| `ratingAPI` | getAll, getByDM, storeBulk, storeMatrix |
| `calculationAPI` | calculateAHP, calculateANP, calculateWP, calculateBorda, calculateAll, getResults, getFinalRanking |

### Helper Functions

```javascript
import { showSuccess, showError, showLoading, closeLoading, confirmDelete } from './api.js';

// Show success notification
showSuccess('Data saved successfully!');

// Show error notification
showError('Failed to save data');

// Show loading spinner
showLoading();
closeLoading();

// Confirm delete action
const confirmed = await confirmDelete('Delete this item?');
if (confirmed) {
    // Proceed with deletion
}
```

---

## 🎨 Custom CSS Classes

### Layout Classes
```css
.nav-link          /* Sidebar navigation links */
.nav-link.active   /* Active navigation state */
```

### Card Classes
```css
.card              /* Base card container */
.card-header       /* Card header section */
.card-body         /* Card body section */
.card-footer       /* Card footer section */
```

### Button Classes
```css
.btn               /* Base button */
.btn-primary       /* Blue primary button */
.btn-secondary     /* Gray secondary button */
.btn-success       /* Green success button */
.btn-danger        /* Red danger button */
.btn-warning       /* Yellow warning button */
.btn-info          /* Cyan info button */
.btn-outline       /* Outlined button */
.btn-sm            /* Small button */
.btn-lg            /* Large button */
```

### Form Classes
```css
.form-group        /* Form field container */
.form-label        /* Field label */
.form-input        /* Text input */
.form-select       /* Select dropdown */
.form-textarea     /* Textarea input */
.form-error        /* Error message */
```

### Table Classes
```css
.table-container   /* Scrollable table wrapper */
.table             /* Base table */
```

### Badge Classes
```css
.badge             /* Base badge */
.badge-primary     /* Blue badge */
.badge-success     /* Green badge */
.badge-danger      /* Red badge */
.badge-warning     /* Yellow badge */
.badge-info        /* Cyan badge */
.badge-gray        /* Gray badge */
```

### Alert Classes
```css
.alert             /* Base alert */
.alert-success     /* Green success alert */
.alert-error       /* Red error alert */
.alert-warning     /* Yellow warning alert */
.alert-info        /* Blue info alert */
```

### Stats Card Classes
```css
.stat-card         /* Base stats card */
.stat-card-blue    /* Blue gradient */
.stat-card-green   /* Green gradient */
.stat-card-purple  /* Purple gradient */
.stat-card-orange  /* Orange gradient */
.stat-card-red     /* Red gradient */
```

### Utility Classes
```css
.spinner           /* Loading spinner */
.empty-state       /* Empty state container */
.empty-state-icon  /* Empty state icon */
.empty-state-title /* Empty state title */
.empty-state-text  /* Empty state text */
.progress          /* Progress bar container */
.progress-bar      /* Progress bar fill */
```

---

## 🔧 Customization

### Change Primary Color

Edit `resources/css/app.css`:
```css
/* Change from blue to purple */
.btn-primary {
    @apply bg-purple-600 text-white hover:bg-purple-700 focus:ring-purple-500;
}
```

### Add New Component

```blade
<!-- resources/views/components/my-component.blade.php -->
<div class="card">
    <div class="card-header">
        <h3>{{ $title }}</h3>
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
```

Usage:
```blade
<x-my-component title="My Title">
    Content here
</x-my-component>
```

---

## 🐛 Troubleshooting

### Issue: Styles not loading
```bash
# Clear cache and rebuild
npm run build
php artisan view:clear
php artisan cache:clear
```

### Issue: API calls fail
```javascript
// Check network tab in browser DevTools
// Verify API base URL in api.js
const API_BASE_URL = '/api';  // Should match your setup
```

### Issue: Dark mode not working
```html
<!-- Ensure html tag has dark class -->
<html lang="id" class="dark">
```

---

## 📊 Performance Tips

1. **Lazy Load Charts**
   ```javascript
   // Load Chart.js only when needed
   import('chart.js').then(Chart => {
       // Create chart
   });
   ```

2. **Debounce Search**
   ```javascript
   const debounce = (fn, delay) => {
       let timeout;
       return (...args) => {
           clearTimeout(timeout);
           timeout = setTimeout(() => fn(...args), delay);
       };
   };
   ```

3. **Optimize Images**
   ```bash
   # Use WebP format
   # Compress images before upload
   ```

---

## 📝 License

MIT License - Feel free to use for educational purposes

---

## 👥 Contributors

- **Developer**: AI Assistant
- **Framework**: Laravel + Tailwind CSS
- **Method**: ANP-WP-BORDA

---

## 🎓 References

- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Chart.js Documentation](https://www.chartjs.org/docs)
- [SweetAlert2 Documentation](https://sweetalert2.github.io/)
- [Laravel Blade Documentation](https://laravel.com/docs/blade)

---

**Happy Decision Making! 🎯**
