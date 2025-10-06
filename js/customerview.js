// Category Management JavaScript
class CategoryManager {
    constructor() {
        this.selectedCategoryId = null;
        this.modal = null;
        this.init();
    }

    init() {
        this.initializeModal();
        this.bindEvents();
        this.loadCategories();
    }

    initializeModal() {
        const modalElement = document.getElementById('categoryModal');
        if (modalElement) {
            this.modal = new bootstrap.Modal(modalElement);
        }
    }

    bindEvents() {
        // Add category form submission
        document.getElementById('addCategoryForm')?.addEventListener('submit', (e) => {
            e.preventDefault();
            this.addCategory();
        });

        // Save category changes
        document.getElementById('saveCategoryBtn')?.addEventListener('click', () => {
            this.updateCategory();
        });

        // Delete category
        document.getElementById('deleteCategoryBtn')?.addEventListener('click', () => {
            this.deleteCategory();
        });

        // View category button clicks (delegated)
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('view-category-btn')) {
                this.openCategoryModal(e.target);
            }
        });
    }

    async loadCategories() {
        try {
            const response = await fetch('../Actions/get_categories_action.php', {
                method: 'GET',
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error('Failed to load categories');
            }

            const categories = await response.json();
            this.renderCategories(categories);
        } catch (error) {
            console.error('Error loading categories:', error);
            alert('Failed to load categories: ' + error.message);
        }
    }

    renderCategories(categories) {
        const tbody = document.querySelector('table tbody');
        if (!tbody) return;

        tbody.innerHTML = categories.map((category, index) => `
            <tr>
                <td>${index + 1}</td>
                <td>${this.escapeHtml(category.category_name)}</td>
                <td>${this.getStatusBadge(category.is_approved)}</td>
                <td>
                    <button class="btn btn-info btn-sm view-category-btn" 
                            data-id="${category.category_id}" 
                            data-name="${this.escapeHtml(category.category_name)}">
                        View / Edit
                    </button>
                </td>
            </tr>
        `).join('');
    }

    getStatusBadge(status) {
        const statusMap = {
            1: { class: 'bg-success', text: 'Approved' },
            2: { class: 'bg-danger', text: 'Rejected' },
            0: { class: 'bg-warning text-dark', text: 'Pending' }
        };
        
        const statusInfo = statusMap[status] || statusMap[0];
        return `<span class="badge ${statusInfo.class}">${statusInfo.text}</span>`;
    }

    openCategoryModal(button) {
        this.selectedCategoryId = button.getAttribute('data-id');
        document.getElementById('catId').value = this.selectedCategoryId;
        document.getElementById('catName').value = button.getAttribute('data-name');
        
        if (this.modal) {
            this.modal.show();
        }
    }

    async addCategory() {
        const form = document.getElementById('addCategoryForm');
        const formData = new FormData(form);
        const categoryName = document.getElementById('newCategoryName').value.trim();

        if (!categoryName) {
            alert('Please enter a category name');
            return;
        }

        try {
            const response = await fetch('../Actions/add_category_action.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            
            if (result.status === 'success') {
                alert(result.message);
                form.reset();
                this.loadCategories(); // Refresh the categories list
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Add category error:', error);
            alert('Add failed: ' + error.message);
        }
    }

    async updateCategory() {
        const form = document.getElementById('editCategoryForm');
        const formData = new FormData(form);

        try {
            const response = await fetch('../Actions/update_category_action.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            
            if (result.status === 'success') {
                alert(result.message);
                if (this.modal) {
                    this.modal.hide();
                }
                this.loadCategories(); // Refresh the categories list
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Update category error:', error);
            alert('Update failed: ' + error.message);
        }
    }

    async deleteCategory() {
        if (!this.selectedCategoryId) {
            alert('No category selected');
            return;
        }

        if (!confirm('Are you sure you want to delete this category?')) {
            return;
        }

        try {
            const formData = new FormData();
            formData.append('category_id', this.selectedCategoryId);

            const response = await fetch('../Actions/delete_category_action.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            
            if (result.status === 'success') {
                alert(result.message);
                if (this.modal) {
                    this.modal.hide();
                }
                this.loadCategories(); // Refresh the categories list
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Delete category error:', error);
            alert('Delete failed: ' + error.message);
        }
    }

    escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
}

// Initialize category manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new CategoryManager();
});