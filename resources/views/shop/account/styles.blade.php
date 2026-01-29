<style>
  /* Account Dashboard Styles */
  .account-dashboard-area {
    background-color: #f8f9fa;
    padding: 40px 0;
  }

  /* Sidebar Styles */
  .account-sidebar {
    background: #fff;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  }

  .account-greeting h5 {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin-bottom: 0;
  }

  .account-nav {
    margin-top: 24px;
  }

  .nav-section {
    margin-bottom: 32px;
  }

  .nav-section:last-child {
    margin-bottom: 0;
  }

  .nav-section-title {
    font-size: 14px;
    font-weight: 700;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid #e8e8e8;
  }

  .nav-section-title.active {
    color: #007bff;
  }

  .nav-section-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .nav-section-list li {
    margin-bottom: 8px;
  }

  .nav-section-list li:last-child {
    margin-bottom: 0;
  }

  .nav-link {
    display: block;
    padding: 8px 0;
    color: #666;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.2s ease;
  }

  .nav-link:hover {
    color: #007bff;
  }

  .nav-link.active {
    color: #007bff;
    font-weight: 600;
  }

  /* Main Content Styles */
  .account-page-title {
    font-size: 28px;
    font-weight: 600;
    color: #333;
    margin-bottom: 24px;
  }

  .account-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    margin-bottom: 24px;
  }

  .account-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e8e8e8;
  }

  .account-card-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
  }

  .account-edit-link {
    font-size: 12px;
    font-weight: 600;
    color: #007bff;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: color 0.2s ease;
  }

  .account-edit-link:hover {
    color: #0056b3;
  }

  .account-card-body {
    padding: 24px;
  }

  /* Personal Profile Styles */
  .account-profile-info {
    color: #333;
  }

  .account-profile-name {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0;
  }

  .account-profile-email {
    font-size: 14px;
    color: #666;
    margin: 0;
  }

  /* Address Book Styles */
  .account-address-label {
    font-size: 11px;
    font-weight: 700;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
  }

  .account-address-details {
    font-size: 14px;
    color: #666;
    line-height: 1.6;
  }

  .account-address-details p {
    margin: 0;
  }

  /* Orders Table Styles */
  .account-orders-table {
    margin: 0;
  }

  .account-orders-table thead th {
    font-size: 12px;
    font-weight: 700;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 16px 12px;
    border-bottom: 2px solid #e8e8e8;
    background: #f8f9fa;
  }

  .account-orders-table tbody td {
    padding: 16px 12px;
    vertical-align: middle;
    border-bottom: 1px solid #e8e8e8;
  }

  .account-orders-table tbody tr:last-child td {
    border-bottom: none;
  }

  .account-order-code {
    font-weight: 600;
    color: #333;
  }

  .account-order-date {
    color: #666;
    font-size: 14px;
  }

  .account-order-item {
    display: inline-block;
  }

  .account-order-item-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
  }

  .account-order-total {
    font-weight: 600;
    color: #333;
  }

  .account-manage-link {
    font-size: 12px;
    font-weight: 600;
    color: #007bff;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: color 0.2s ease;
  }

  .account-manage-link:hover {
    color: #0056b3;
  }

  /* Responsive */
  @media (max-width: 991.98px) {
    .account-sidebar {
      margin-bottom: 24px;
    }

    .account-page-title {
      font-size: 24px;
    }
  }

  @media (max-width: 767.98px) {
    .account-card-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 12px;
    }

    .account-address-label {
      margin-top: 16px;
    }

    .account-address-label:first-child {
      margin-top: 0;
    }
  }
</style>
