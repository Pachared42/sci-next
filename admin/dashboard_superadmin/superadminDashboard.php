<?php
require_once __DIR__ . '/../../controller/controllerSuperadmin.php';
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCi_ADMIN</title>

    <link rel="icon" type="image/svg+xml" href="/sci-next/img/sci-next.svg" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wdth,wght@62.5..100,100..900&family=Noto+Sans:ital,wdth,wght@0,62.5..100,100..900;1,62.5..100,100..900&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- <link rel="stylesheet" href="superadminDashboard.css"> -->

</head>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Noto Sans Thai", "Noto Sans", sans-serif;
    }

    body {
        background-color: #000000;
        margin-left: 220px;
        color: white;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        position: relative;
        transition: margin-left 0.5s ease;
    }

    body.sidebar-collapsed {
        margin-left: 0;
    }

    .form-header-upload {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .tooltip {
        position: relative;
        cursor: pointer;
    }

    .tooltip::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        background-color: #000000;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 10px;
        border-radius: 15px;
        font-size: 14px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        font-family: "Noto Sans Thai", "Noto Sans", sans-serif;
        z-index: 1000;
        min-width: 300px;
        white-space: normal;
        word-wrap: break-word;
        line-height: 1.6;
    }

    .tooltip:hover::after {
        opacity: 1;
    }

    .h-text-upload {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        font-size: clamp(16px, 4vw, 30px);
        font-weight: bold;
        color: #8739F9;

        p {
            font-size: 14px;
            color: #E1DFE9;
        }
    }

    .h-text-sale {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        font-size: clamp(16px, 4vw, 30px);
        font-weight: bold;
        color: #8739F9;

        p {
            font-size: 14px;
            color: #E1DFE9;
        }
    }

    .center-screen {
        height: 70vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 30px;
        color: #333333;
    }

    .p-text-upload {
        color: #ff0000;
    }

    .h2-text-upload {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 28px;
        font-weight: bold;
        color: #8739F9;
        margin-bottom: 15px;
    }

    .tab-divider-category {
        border: none;
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin: 15px 0px 15px 0px;
    }

    .tab-divider-admin {
        border: none;
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin: 0px 0px 15px 0px;
    }

    .container {
        position: relative;
        z-index: 1;
    }

    #tabLoadingBar {
        position: fixed;
        top: 0;
        left: 0;
        height: 5px;
        background: linear-gradient(90deg, #2176FF, #00f2fe);
        width: 0;
        transition: width 0.4s ease, opacity 0.4s ease;
        z-index: 9999;
    }

    /* Navbar */
    .navbar {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #000000;
        color: #ffffff;
        padding: 10px 20px;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.3);
    }

    .hamburger {
        position: absolute;
        left: 20px;
        display: flex;
        align-items: center;
    }

    .hamburger svg {
        transition: transform 0.3s ease-out, fill 0.3s ease-out;
        cursor: pointer;
    }

    .hamburger:hover svg {
        transform: scale(1.1);
        fill: #908E9B;
    }

    .logo-name {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo {
        width: 36px;
        height: auto;
        margin-right: 10px;
    }


    .site-name {
        font-size: 26px;
        font-weight: bold;
    }

    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 64px;
        left: 0;
        width: 220px;
        height: calc(100vh - 68px);
        padding: 10px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background-color: #000000;
        border-right: 1px solid rgba(255, 255, 255, 0.2);
        transform: translateX(0);
        opacity: 1;
        transition: transform 0.5s ease, opacity 0.5s ease, width 0.5s ease;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .sidebar::-webkit-scrollbar {
        width: 10px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: #000000;
        border-radius: 50px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .sidebar .content {
        display: block;
    }

    .sidebar.closed {
        transform: translateX(-100%);
    }

    .sidebar.closed .content {
        display: none;
    }

    .main-tabs {
        margin-bottom: 5px;
    }

    .main-tabs h3 {
        font-size: 12px;
        font-weight: bold;
        color: #8739F9;
    }

    /* กำหนดลักษณะของเส้น hr */
    .tab-divider {
        border: none;
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin: 1px;
    }

    .main-tabs-upload {
        margin-bottom: 5px;
    }

    .main-tabs-upload h3 {
        margin-top: 5px;
        font-size: 12px;
        font-weight: bold;
        color: #8739F9;
    }

    .main-tabs-products {
        margin-bottom: 5px;
    }

    .main-tabs-products h3 {
        margin-top: 5px;
        font-size: 12px;
        font-weight: bold;
        color: #8739F9;
    }

    /* ปุ่มเมนู */
    .tab {
        padding: 10px 0 10px 15px;
        color: #ffffff;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: 0.3s ease;
        display: flex;
        align-items: center;
        gap: 4px;
        position: relative;
        border-radius: 15px;
        margin: 2px 0px;
    }

    .tab i,
    .tab span.material-icons {
        margin-right: 4px;
    }

    .tab:hover {
        background-color: rgba(211, 211, 211, 0.3);
    }

    .tab:active,
    .tab.active {
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        position: relative;
        border-radius: 15px;
    }


    .tab:active .material-icons,
    .tab.active .material-icons {
        color: #ffffff;
    }

    .tab.account:hover:active,
    .tab.account.active:hover {
        color: #ffffff;
    }

    .tab.account:hover:active .material-icons,
    .tab.account.active:hover .material-icons {
        color: #ffffff;
    }

    /* ปุ่มออกจากระบบ */
    .logout {
        padding: 10px 15px;
        background: #ff4b4b;
        text-align: center;
        font-weight: 600;
        color: white;
        border-radius: 15px;
        border: none;
        transition: 0.3s ease;
        margin-top: 5px;
        text-decoration: none;
    }

    .logout:hover {
        background: #ff0000;
    }

    .account {
        padding: 10px 15px;
        background: #37B9F1;
        text-align: center;
        font-weight: 600;
        color: white;
        border-radius: 15px;
        border: none;
        transition: 0.3s ease;
        margin-top: 5px;
        text-decoration: none;
    }

    .account:hover {
        background: #1789BD;
    }

    .email-input-wrapper {
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
    }

    .email-input-wrapper input {
        width: 100%;
        padding-right: 40px;
    }

    .toggle-email-btn {
        position: absolute;
        top: 40%;
        right: 20px;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #666666;
        padding: 0;
        display: flex;
        align-items: center;
    }

    .content {
        display: none;
        padding: 0px 15px 0px 15px;
    }

    .show {
        display: block;
    }

    .product-container {
        display: flex;
        flex-wrap: wrap;
    }

    .product-card {
        border: 1px solid #ddd;
        padding: 10px;
        margin: 10px;
        width: 200px;
        text-align: center;
    }

    .product-card img {
        width: 100%;
        height: auto;
    }

    @keyframes gradientAnimation {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .highlight {
        background: linear-gradient(270deg, #FF6347, #FF4500, #FF6347);
        background-size: 400% 400%;
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: gradientAnimation 3s ease infinite;
    }

    .pagination {
        text-align: center;
    }

    .page-btn {
        font-size: 16px;
        color: #000000;
        font-weight: bold;
        padding: 5px 12px;
        margin: 4px;
        cursor: pointer;
        background-color: #ffffff;
        border: none;
        border-radius: 5px;
    }

    .page-btn:hover {
        background-color: #dddddd;
        color: #000000;
    }

    .page-btn.active {
        background-color: #555555;
        color: #ffffff;
    }

    #scrollTopBtn {
        display: none;
        position: fixed;
        bottom: 15px;
        right: 15px;
        z-index: 99;
        background-color: #2176FF;
        padding: 10px;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        transition: background-color 0.3s, transform 0.3s, opacity 0.3s;
        overflow: visible;
    }

    #scrollTopBtn:hover {
        background-color: #1056CC;
        transform: translateY(-3px);
        opacity: 1;
    }

    #scrollTopBtn svg {
        display: block;
        margin: auto;
        filter: drop-shadow(3px 3px 4px rgba(0, 0, 0, 0.3));
    }

    #scrollTopBtn::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 110%;
        left: 50%;
        transform: translateX(-50%);
        background: #000000;
        color: #ffffff;
        padding: 5px 10px;
        border-radius: 10px;
        font-size: 14px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    }

    #scrollTopBtn:hover::after {
        opacity: 1;
    }

    #scrollTopBtnSoftDrink {
        display: none;
        position: fixed;
        bottom: 15px;
        right: 15px;
        z-index: 99;
        background-color: #2176FF;
        padding: 10px;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        transition: background-color 0.3s, transform 0.3s, opacity 0.3s;
        overflow: visible;
    }

    #scrollTopBtnSoftDrink:hover {
        background-color: #1056CC;
        transform: translateY(-3px);
        opacity: 1;
    }

    #scrollTopBtnSoftDrink svg {
        display: block;
        margin: auto;
        filter: drop-shadow(3px 3px 4px rgba(0, 0, 0, 0.3));
    }

    #scrollTopBtnSoftDrink::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 110%;
        left: 50%;
        transform: translateX(-50%);
        background: #000000;
        color: #ffffff;
        padding: 5px 10px;
        border-radius: 10px;
        font-size: 14px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    }

    #scrollTopBtnSoftDrink:hover::after {
        opacity: 1;
    }

    #scrollTopBtnFreshFood {
        display: none;
        position: fixed;
        bottom: 15px;
        right: 15px;
        z-index: 99;
        background-color: #2176FF;
        padding: 10px;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        transition: background-color 0.3s, transform 0.3s, opacity 0.3s;
        overflow: visible;
    }

    #scrollTopBtnFreshFood:hover {
        background-color: #1056CC;
        transform: translateY(-3px);
        opacity: 1;
    }

    #scrollTopBtnFreshFood svg {
        display: block;
        margin: auto;
        filter: drop-shadow(3px 3px 4px rgba(0, 0, 0, 0.3));
    }

    #scrollTopBtnFreshFood::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 110%;
        left: 50%;
        transform: translateX(-50%);
        background: #000000;
        color: #ffffff;
        padding: 5px 10px;
        border-radius: 10px;
        font-size: 14px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    }

    #scrollTopBtnFreshFood:hover::after {
        opacity: 1;
    }

    #scrollTopBtnSnackFood {
        display: none;
        position: fixed;
        bottom: 15px;
        right: 15px;
        z-index: 99;
        background-color: #2176FF;
        padding: 10px;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        transition: background-color 0.3s, transform 0.3s, opacity 0.3s;
        overflow: visible;
    }

    #scrollTopBtnSnackFood:hover {
        background-color: #1056CC;
        transform: translateY(-3px);
        opacity: 1;
    }

    #scrollTopBtnSnackFood svg {
        display: block;
        margin: auto;
        filter: drop-shadow(3px 3px 4px rgba(0, 0, 0, 0.3));
    }

    #scrollTopBtnSnackFood::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 110%;
        left: 50%;
        transform: translateX(-50%);
        background: #000000;
        color: #ffffff;
        padding: 5px 10px;
        border-radius: 10px;
        font-size: 14px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    }

    #scrollTopBtnSnackFood:hover::after {
        opacity: 1;
    }


    /* สไตล์ของตาราง */
    table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 25px;
        overflow: hidden;
        text-align: center;
        margin: 10px 0 20px 0;
    }

    th {
        background-color: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 12px;
        font-size: 16px;
        max-width: 200px;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }

    td {
        border-bottom: 1px solid rgba(0, 0, 0, 0.3);
        background: #ffffff;
        color: #000000;
        padding: 12px;
        max-width: 200px;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    tbody tr {
        cursor: pointer;
    }

    td img {
        border-radius: 20px;
    }

    .center-checkbox {
        text-align: center;
        padding: 12px 0 12px 6px;
    }

    .center-checkbox input {
        transform: scale(1);
        vertical-align: middle;
    }

    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(235px, 1fr));
        gap: 20px;
        padding: 20px 0 20px 0;
    }

    .card {
        background: #ffffff;
        border-radius: 30px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s;
        cursor: pointer;

        .label {
            font-weight: 400;
            font-size: 18px;
            color: #565360;
        }

        p {
            font-size: 14px;
            color: #000000;
            font-weight: bold;
        }
    }

    .name-grid {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .product-image-container {
        position: relative;
        display: inline-block;
    }

    .out-of-stock-label-table {
        top: 10px;
        left: 50%;
        background-color: rgba(255, 0, 0, 0.8);
        color: #ffffff;
        padding: 5px 10px;
        font-weight: bold;
        font-size: 15px;
        border-radius: 12.5px;
    }

    .out-of-stock-label {
        position: absolute;
        top: 45px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(255, 0, 0, 0.8);
        color: #ffffff;
        padding: 5px 10px;
        font-weight: bold;
        font-size: 16px;
        border-radius: 12.5px;
    }

    .product-image {
        width: 45%;
        height: auto;
        object-fit: cover;
        display: block;
        margin-left: auto;
        margin-right: auto;
        margin-top: 10px;
        border-radius: 20px;
    }

    .card-content {
        padding: 15px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        color: #000000;

        h3 {
            text-align: center;
        }
    }

    .barcode {
        text-align: center;
        justify-content: center;
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 16px;
        font-weight: bold;

        svg {
            width: 30px;
            height: 30px;
            fill: #565360;
        }
    }

    .card-actions {
        margin-top: 10px;
        display: flex;
        gap: 10px;
    }

    .card-actions button,
    .card-actions a {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 1;
        padding: 8px;
        border: none;
        background: #37B9F1;
        color: #ffffff;
        text-align: center;
        text-decoration: none;
        border-radius: 15px;
        font-size: 16px;
        cursor: pointer;
        font-weight: bold;
        gap: 4px;

        svg {
            width: 24px;
            height: 24px;
            fill: #ffffff;
        }
    }

    .card-actions a {
        background: #DC143C;
    }

    .card-actions button:hover {
        background: #1789BD;
    }

    .card-actions a:hover {
        background-color: #ff0000;
    }

    #uplord_prodect {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: auto;
        overflow: hidden;
        box-sizing: border-box;
    }

    .form-container {
        flex: 1;
    }

    .form-upload {
        margin-top: 15px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        border-radius: 10px;
    }

    .form-container-product {
        display: flex;
        column-gap: 10px;
        flex-wrap: wrap;
        width: 100%;
    }

    .from-container-stock {
        display: flex;
        column-gap: 10px;
        flex-wrap: wrap;
        width: 100%;
    }

    .form-group {
        display: flex;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 12px;
        border-radius: 20px;
        flex: 1;
        min-width: 340px;
        margin-bottom: 15px;
    }

    .form-group label {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 14px;
        color: #777777;
        white-space: nowrap;
    }

    .form-group input {
        border: none;
        outline: none;
        font-size: 14px;
        flex: 1;
        background: transparent;
        min-width: 120px;
        color: #ffffff;
    }

    label svg {
        width: 24px;
        height: 24px;
        margin: 0 2px 0 8px;
        fill: #ffffff;
    }

    label span {
        color: #ffffff;
        margin: 0 2px 0 8px;
    }

    input[type="file"] {
        display: none;
    }

    .custom-file-upload {
        width: 100%;
        display: inline-block;
        padding: 12px;
        color: #ffffff;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .name-group {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .half-width {
        width: 48%;
    }

    .full-width {
        width: 100%;
    }

    .btn-upload {
        display: flex;
        width: 100%;
        justify-content: center;
        align-items: center;
        gap: 4px;
        background: #8739F9;
        color: white;
        border: none;
        padding: 20px;
        font-size: 20px;
        font-weight: 500;
        border-radius: 20px;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-upload:hover {
        background: #5419B5;
    }

    /* Style for the popup */
    .edit-popup {
        display: none;
        position: fixed;
        top: 0;
        right: 0;
        width: 400px;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        z-index: 9999;
        justify-content: flex-start;
        align-items: flex-start;
    }

    /* Content inside the popup */
    .popup-content {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #fff;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        overflow-y: auto;
    }

    /* Heading inside the popup */
    .popup-content h3 {
        margin: 15px 0px 30px 0px;
        color: #333;
        font-size: 24px;
        text-align: center;
    }

    /* Label for inputs */
    .popup-content label {
        display: block;
        margin: 10px 0 5px;
        font-size: 16px;
    }

    /* Style for input fields */
    .popup-content input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }


    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        position: sticky;
        top: 64px;
        background: #000000;
        z-index: 1000;
        padding: 8px 0 8px 0;
    }

    .no-items-message {
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        font-size: 16px;
        color: #000000;
        padding: 20px;
        gap: 4px;

        svg {
            fill: #333333;
            width: 24px;
            height: 24px;
            ;
        }
    }

    .search-container {
        width: 50%;
        display: flex;
        align-items: center;
        background: #ffffff;
        border: 1px solid #dddddd;
        border-radius: 15px;
        padding: 4px;
        transition: box-shadow 0.2s ease;
    }

    .search-container:focus-within {
        border-color: #000000;
    }

    .search-input {
        border: none;
        outline: none;
        padding: 8px 12px;
        font-size: 14px;
        flex: 1;
        background: transparent;
    }

    .search-button {
        background: #000000;
        border: none;
        padding: 8px;
        border-radius: 12.5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s ease;
    }

    .search-button:hover {
        background: #333333;
    }

    .search-button svg {
        width: 24px;
        height: 24px;
        fill: #ffffff;
    }

    .btn {
        position: relative;
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px;
    }

    .btn-container {
        margin-top: 5px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn svg {
        width: 35px;
        height: 35px;
        transition: 0.3s;
    }

    .btn-dried-food svg {
        fill: #4CAF50;
    }

    .btn-soft-drink svg {
        fill: #4CAF50;
    }

    .btn-fresh-food svg {
        fill: #4CAF50;
    }

    .btn-snack svg {
        fill: #4CAF50;
    }

    .btn-stationery svg {
        fill: #4CAF50;
    }

    .btn-edit-product-localStorage svg {
        fill: #FFA500;
    }

    /* Modal background */
    .modal-flex {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
    }

    /* รูปภาพฝั่งซ้าย */
    .modal-image {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 320px;
        height: 100%;
        flex-shrink: 0;
    }

    #editImagePreview {
        width: 250px;
        height: auto;
        border-radius: 40px;
    }

    .image-warning {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        color: #DC143C;
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
        text-align: center;

        svg {
            width: 18px;
            height: 18px;
            fill: #DC143C;
        }
    }

    .modal-fields {
        color: #000000;
        flex: 1;

        label {
            font-size: 15px;
            font-weight: 200;
            color: #ffffff;
        }
    }

    .field-row {
        display: flex;
        gap: 10px;
        justify-content: space-between;
    }

    .field-row div {
        flex: 1;
    }

    #editModal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    /* Modal box */
    .modal-content {
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 15px;
        border-radius: 30px;
        animation: bounceIn 0.4s ease;
    }

    .modal-content h3 {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        text-align: center;
        margin-bottom: 15px;
        font-size: 26px;
        color: #ffffff;

        svg {
            width: 35px;
            height: 35px;
            fill: #ffffff;
        }
    }

    .modal-content input[type="text"],
    .modal-content input[type="number"] {
        width: 100%;
        padding: 15px;
        margin-top: 5px;
        margin-bottom: 10px;
        border: 1px solid #000000;
        border-radius: 15px;
        font-size: 14px;
        font-weight: bold;
        transition: border-color 0.3s;
        background-color: rgba(255, 255, 255, 0.2);
    }

    .modal-content input:focus {
        border-color: #DC143C;
        outline: none;
    }

    .modal-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        font-size: 18px;
        padding: 15px 20px;
        border-radius: 10px;
        margin-top: 10px;
        cursor: pointer;
        border: none;
        transition: background-color 0.3s;
    }

    .modal-btn-save {
        width: 70%;
        background-color: #4CAF50;
        color: #ffffff;
        gap: 4px;
        border-radius: 20px;

        svg {
            fill: #ffffff;
            width: 24px;
            height: 24px;
        }
    }

    .modal-btn-save:hover {
        background-color: #45a049;
    }

    .modal-btn-cancel {
        width: 30%;
        background-color: #DC143C;
        color: #ffffff;
        gap: 4px;
        border-radius: 20px;

        svg {
            fill: #ffffff;
            width: 24px;
            height: 24px;
        }
    }

    .modal-btn-cancel:hover {
        background-color: #E05268;
    }

    .btn-delete-product svg {
        fill: #DC143C;
    }

    .highlight-text {
        color: #DC143C;
        font-weight: bold;
        display: inline;
        text-decoration: underline;
        text-decoration-color: #DC143C;
        text-decoration-thickness: 2px;
        text-underline-offset: 2px;
    }

    .custom-modal-content {
        width: 400px;
        max-width: 90%;
        height: auto;
        max-height: 80%;
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 25px;
        border-radius: 40px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        text-align: center;
        animation: bounceIn 0.4s ease;
        transform-origin: center;

        p {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 40px;
        }

        span {
            font-size: 160px;
            color: #F2F5F5;
            margin-bottom: 40px;
        }
    }

    .modal-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-top: 20px;
    }

    .btn-Confirm {
        font-size: 16px;
        gap: 4px;
        padding: 15px 20px;
        font-weight: bold;
        border: none;
        border-radius: 20px;
        cursor: pointer;
    }

    .btn-danger {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #DC143C;
        color: #ffffff;

        svg {
            width: 24px;
            fill: #ffffff;
        }
    }

    .btn-danger:hover {
        background-color: #E05268;
    }

    .btn-success {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #4CAF50;
        color: #ffffff;

        svg {
            width: 24px;
            fill: #ffffff;
        }
    }

    .btn-success:hover {
        background-color: #45a049;
    }

    .btn-secondary {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #555555;

        svg {
            width: 24px;
            fill: #555555;
        }
    }

    .btn-secondary:hover {
        background-color: #dddddd;
    }

    .custom-toast {
        display: none;
        position: fixed;
        top: 30px;
        right: 20px;
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 15px 25px;
        border-radius: 15px;
        font-weight: 600;
        z-index: 9999;
        animation: bounceIn 0.4s ease, fadeOut 4s ease-in-out forwards;

        p {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;

            span {
                width: 24px;
                height: 24px;
                fill: #4CAF50;
            }
        }
    }

    .alert-toast {
        display: none;
        position: fixed;
        top: 8px;
        right: 8px;
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 10px 15px;
        border-radius: 15px;
        font-weight: 600;
        z-index: 9999;
        animation: bounceIn 0.4s ease, fadeOut 4s ease-in-out forwards;
        gap: 4px;

        span {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
    }

    .alert-toast.success-toast {
        display: none;
        position: fixed;
        top: 8px;
        right: 8px;
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 10px 15px;
        border-radius: 15px;
        font-weight: 600;
        z-index: 9999;
        animation: bounceIn 0.4s ease, fadeOut 4s ease-in-out forwards;
        gap: 4px;

        span {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
    }

    .alert-toast.success {
        display: none;
        position: fixed;
        top: 8px;
        right: 8px;
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 10px 15px;
        border-radius: 15px;
        font-weight: 600;
        z-index: 9999;
        animation: bounceIn 0.4s ease, fadeOut 4s ease-in-out forwards;
        gap: 4px;

        span {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
    }

    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.7);
        }

        60% {
            opacity: 1;
            transform: scale(1.05);
        }

        80% {
            transform: scale(0.95);
        }

        100% {
            transform: scale(1);
        }
    }

    @keyframes fadeOut {

        0%,
        90% {
            opacity: 1;
        }

        100% {
            opacity: 0;
        }
    }

    @keyframes fadeInBackground {
        0% {
            background-color: rgba(0, 0, 0, 0);
        }

        100% {
            background-color: rgba(0, 0, 0, 0.4);
        }
    }

    .btn-filter svg {
        fill: #FFA500;
    }

    .btn-grid svg {
        fill: #1E90FF;
    }

    .btn-table svg {
        fill: #DC143C;
    }

    .btn:hover svg {
        opacity: 0.7;
    }

    .btn::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 90%;
        left: 50%;
        transform: translateX(-50%);
        background: #000000;
        color: #ffffff;
        padding: 6px 10px;
        font-size: 14px;
        white-space: nowrap;
        border-radius: 10px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    }

    .btn:hover::after {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(-5px);
    }

    .download-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
        margin-top: 15px;
    }

    .download-template {
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border-radius: 20px;
        padding: 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        transition: transform 0.2s ease;

        p {
            justify-content: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 50px;
        }

        svg {
            width: 28px;
            height: 28px;
            fill: #ffffff;
        }
    }

    .btn-download {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        background-color: #37B9F1;
        border-radius: 15px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .btn-download::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 110%;
        left: 50%;
        transform: translateX(-50%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: #000000;
        color: #ffffff;
        padding: 6px 10px;
        font-size: 14px;
        white-space: nowrap;
        border-radius: 12.5px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    }

    .btn-download:hover {
        background-color: #1789BD;
    }

    .filter-menu {
        display: none;
        background-color: #ffffff;
        color: #000000;
        border-radius: 10px;
        padding: 0 10px 10px 10px;
        position: absolute;
        top: 78px;
        right: 75px;
        width: 150px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);

        option {
            background-color: #ffffff;
            color: #000000;
            padding: 10px;
            font-size: 14px;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        optgroup {
            background-color: #000000;
            font-weight: 600;
            color: #ffffff;
        }
    }

    .filter-menu select,
    .filter-menu input {
        width: 100%;
        padding: 8px;
        margin-top: 10px;
        border: 1px solid #cccccc;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 600;
        background: #f9f9f9;
    }

    .filter-menu button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        width: 100%;
        padding: 8px;
        background-color: #2176FF;
        color: #ffffff;
        border: none;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        border-radius: 10px;
        transition: background-color 0.3s ease;

        svg {
            width: 24px;
            height: 24px;
            fill: #ffffff;
        }
    }

    .filter-menu button:hover {
        background-color: #1056CC;
    }

    .parent {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: auto auto auto auto auto;
        gap: 20px;
    }

    /* กำหนดตำแหน่ง */
    .div1 {
        grid-area: 1 / 1 / 2 / 2;
    }

    .div2 {
        grid-area: 1 / 2 / 2 / 3;
    }

    .div3 {
        grid-area: 1 / 3 / 2 / 4;
    }

    .div4 {
        grid-area: 1 / 4 / 2 / 5;
    }

    .div5 {
        grid-area: 2 / 1 / 3 / 2;
        /* แถว 2 คอลัมน์ 1 */
    }

    .div6 {
        grid-area: 2 / 2 / 3 / 3;
        /* แถว 2 คอลัมน์ 2 */
    }

    .div7 {
        grid-area: 2 / 3 / 3 / 4;
        /* แถว 2 คอลัมน์ 3 */
    }

    .div8 {
        grid-area: 2 / 4 / 3 / 5;
        /* แถว 2 คอลัมน์ 4 */
    }

    .div9 {
        grid-area: 3 / 1 / 6 / 5;
    }

    .div10 {
        grid-area: 3 / 4 / 6 / 5;
    }

    /* Card ยอดขายแต่ละช่อง */
    .stat-card {
        position: relative;
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 30px;
        padding: 10px;
        color: white;
        overflow: visible;
        width: 100%;
        height: 100px;
        max-width: 300px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .stat-card span {
        font-size: 50px;
        color: var(--accent);
    }

    .stat-card h5 {
        font-size: 26px;
        font-weight: 500;
        margin: 0;
        opacity: 0.85;
    }

    .stat-card p {
        position: absolute;
        bottom: -30px;
        right: -10px;
        font-size: 45px;
        font-weight: bold;
        margin: 0;
        background: linear-gradient(90deg, #37B9F1, #F2F5F5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        color: transparent;
    }


    .stat-card p .unit {
        font-size: 20px;
        margin-left: 4px;
        color: #ccc;
        font-weight: bold;
    }

    .chart-container {
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 30px;
        padding: 20px;
    }

    .chart-container h4 {
        font-size: 26px;
        color: #E1DFE9;
        margin-bottom: 10px;
        text-align: center;
    }

    #order {
        margin-top: 60px;
        padding: 0 20px 20px 20px;
    }

    .order-date-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 10px 0;
    }

    .order-date-label {
        font-weight: bold;
        font-size: 16px;
        color: #ffffff;
    }

    #order-date-picker {
        padding: 6px 10px;
        font-size: 15px;
        border: 1px solid #ccc;
        border-radius: 12.5px;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    #graph {
        margin-top: 68px;
        padding: 20px;
    }

    #update_product {
        margin-top: 68px;
        padding: 20px;
    }

    #upload_prodect {
        margin-top: 68px;
        padding: 20px;
    }

    #upload_file_excal {
        margin-top: 60px;
        padding: 20px;
    }

    #admin_signup {
        margin-top: 68px;
        padding: 20px;
    }

    #employee_signup {
        margin-top: 68px;
        padding: 20px;
    }

    #dried_food {
        margin-top: 60px;
        padding: 0 20px 20px 20px;
    }

    #soft_drink {
        margin-top: 60px;
        padding: 0 20px 20px 20px;
    }

    #fresh_food {
        margin-top: 60px;
        padding: 0 20px 20px 20px;
    }

    #snack_food {
        margin-top: 60px;
        padding: 0 20px 20px 20px;
    }

    #stationery {
        margin-top: 60px;
        padding: 0 20px 20px 20px;
    }

    #customer {
        margin-top: 68px;
        padding: 20px;
    }

    #sci_admin {
        margin-top: 68px;
        padding: 20px;
    }

    #employee {
        margin-top: 68px;
        padding: 20px;
    }

    #account {
        margin-top: 68px;
        padding: 20px;
    }

    #food_bank_check {
        margin-top: 60px;
        padding: 0 20px 20px 20px;
    }

    #local_drink_check {
        margin-top: 60px;
        padding: 0 20px 20px 20px;
    }

    #fastfood_check {
        margin-top: 60px;
        padding: 0 20px 20px 20px;
    }

    #snack_check {
        margin-top: 60px;
        padding: 0 20px 20px 20px;
    }

    #stationery_check {
        margin-top: 60px;
        padding: 0 20px 20px 20px;
    }

    .category-buttons {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 15px;
    }

    .category-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px 12px;
        background-color: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #ffffff;
        border: none;
        border-radius: 15px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        transition: background 0.3s;
    }

    .category-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .category-btn.selected {
        background-color: #E57300;
        color: #ffffff;
    }

    .collapsible-toggle {
        display: flex;
        align-items: center;
        color: #ffffff;
        padding: 10px 20px 10px 15px;
        border: none;
        border-radius: 15px;
        cursor: pointer;
        font-size: 16px;
        width: 190px;
        justify-content: space-between;
        background-color: transparent;
        transition: background-color 0.3s ease;

        button .material-icons {
            margin-right: 4px;
        }
    }

    .collapsible-toggle:hover {
        background-color: #555555;
    }

    .collapsible-toggle svg {
        transition: transform 0.3s ease;
    }

    /* ทำให้ลูกศรหมุนเมื่อเมนูเปิด */
    .collapsible-toggle[aria-expanded="true"] svg {
        transform: rotate(180deg);
        transition: transform 0.3s ease;
    }

    /* ปรับสไตล์ของเมนู */
    .menu {
        display: block;
        overflow: hidden;
        max-height: 0;
        padding: 0 0 0 15px;
        border-radius: 5px;
        transition: max-height 0.3s ease-out, padding 0.3s ease;
    }

    /* แสดงเมนูเมื่อคลาส active ถูกเพิ่ม */
    .menu.active {
        max-height: 250px;
        padding: 0 0 0 15px;
    }

    .badge {
        position: absolute;
        top: 2px;
        right: 145px;
        background-color: #ff4b4b;
        color: #ffffff;
        font-size: 10px;
        font-weight: bold;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .input-error {
        border-color: #e53935;
        outline-color: #e53935;
    }

    .error-message {
        color: #e53935;
        font-size: 0.8rem;
        margin-top: 2px;
        display: block;
    }

    .preview-error {
        color: #e53935;
        font-weight: 600;
        cursor: pointer;
        position: relative;
        animation: shake 0.3s ease-in-out;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        20%,
        60% {
            transform: translateX(-5px);
        }

        40%,
        80% {
            transform: translateX(5px);
        }
    }

    th input[type="checkbox"] {
        width: 28px;
        height: 28px;
        cursor: pointer;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.2);
        position: relative;
        box-sizing: border-box;
        padding: 0;
        margin: 0;
        outline: none;
    }

    th input[type="checkbox"]:checked {
        background-color: #000000;
        background-image: url('/sci-next/img/done_all.png');
        background-size: 20px 20px;
        background-position: center;
        background-repeat: no-repeat;
    }

    td input[type="checkbox"].row-checkbox {
        width: 28px;
        height: 28px;
        cursor: pointer;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: 50%;
        background-color: #111111;
        position: relative;
        box-sizing: border-box;
        padding: 0;
        margin: 0;
        outline: none;
        vertical-align: middle;
        text-align: center;
    }

    td input[type="checkbox"].row-checkbox:checked {
        background-color: #000000;
        background-image: url('/sci-next/img/check.png');
        background-size: 20px 20px;
        background-position: center;
        background-repeat: no-repeat;
    }

    th input[type="checkbox"]:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .upload-container {
        display: flex;
        align-items: flex-start;
        gap: 20px;
    }

    .product-preview-box {
        width: 280px;
        height: auto;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
    }

    .product-preview-image img {
        max-width: 280px;
        max-height: 280px;
        object-fit: cover;
        border-radius: 20px;
    }

    .product-details {
        margin-top: 10px;
        max-width: 500px;
        color: #333;
    }

    .detail-group {
        display: flex;
        text-align: left;
        align-items: center;
        margin-bottom: 15px;
        padding: 10px 14px;
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        transition: background 0.3s;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .detail-group svg {
        margin-right: 10px;
        fill: #ffffff;
        flex-shrink: 0;
    }

    .detail-group div {
        flex: 1;
        font-size: 16px;
        color: #ffffff;
    }

    .detail-inline {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .detail-inline p {
        flex: 1 1 48%;
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 10px 14px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        transition: background 0.3s;
    }

    .detail-inline .material-icons {
        margin-right: 10px;
        font-size: 24px;
        color: #ffffff;
    }

    .detail-group .material-icons {
        margin-right: 10px;
        font-size: 24px;
        color: #ffffff;
    }

    #previewName,
    #previewBarcode,
    #previewPrice,
    #previewCost,
    #previewStock,
    #previewReorderLevel {
        color: #222;
    }


    .profile-container {
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 40px;
        position: relative;
    }

    .cover {
        padding: 50px;
    }

    .profile-card {
        padding: 90px 15px 15px;
        border-bottom-left-radius: 40px;
        border-bottom-right-radius: 40px;
        position: relative;
        align-items: center;
    }

    .profile-image {
        position: absolute;
        top: -70px;
        left: 415px;
    }

    .profile-image img {
        width: 160px;
        height: 160px;
        background-color: #ffffff;
        border-radius: 30px;
        object-fit: cover;
        border: 6px solid #111111;
    }

    .upload-btn {
        position: absolute;
        bottom: -5px;
        right: -10px;
        background: #111111;
        color: #ffffff;
        width: 45px;
        height: 45px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        cursor: pointer;
        transition: background 0.3s;
    }

    .upload-btn:hover {
        background: #222222;
    }

    .upload-btn i {
        font-size: 22px;
    }

    #file-input {
        display: none;
    }

    .profile-details {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 20px;
        padding: 15px;
        border-radius: 30px;
    }

    .profile-title {
        font-size: 24px;
        font-weight: bold;
        color: #E1DFE9;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        position: relative;
        display: inline-block;
        padding-bottom: 5px;
        margin-bottom: 15px;
    }

    .profile-title::after {
        content: "";
        width: 50px;
        height: 4px;
        position: absolute;
        left: 50%;
        bottom: 0;
        transform: translateX(-50%);
        border-radius: 2px;
    }

    .profile-row {
        display: flex;
        gap: 15px;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .profile-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
        flex: 1;
        min-width: 45%;
    }

    .profile-info label {
        display: flex;
        font-weight: 200;
        color: #908E9B;
        gap: 4px;
    }

    .profile-info label .material-icons {
        font-size: 24px;
        color: #908E9B;
    }

    .profile-info input {
        padding: 20px;
        border: 1px solid #000000;
        color: #ffffff;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        width: 100%;
        font-size: 16px;
        font-weight: bold;
        transition: 0.3s;
        margin-bottom: 20px;
    }

    .profile-info input:focus {
        border: 1px solid #DC143C;
        outline: none;
    }

    .confirm-btn {
        margin-top: 5px;
        width: auto;
        padding: 20px;
        background-color: #8739F9;
        color: #ffffff;
        font-size: 20px;
        font-weight: 500;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    .confirm-btn:hover {
        background-color: #5419B5;
    }

    .action-dropdown {
        position: relative;
        display: inline-block;
    }

    .action-dropdown button {
        background-color: #eeeeee;
        border: none;
        font-size: 25px;
        padding: 5px 15px;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .action-dropdown button:hover {
        background-color: #dddddd;
    }

    .dropdown-content {
        display: block;
        visibility: hidden;
        opacity: 0;
        position: absolute;
        right: -20px;
        top: calc(-50% + 5px);
        background-color: #ffffff;
        min-width: 85px;
        z-index: 1;
        border-radius: 10px;
        overflow: hidden;
        transform: translateY(-10px);
        transition: opacity 0.2s ease, transform 0.2s ease;
    }

    .dropdown-content a {
        color: #000000;
        padding: 10px 5px 10px 5px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: background 0.2s;
        white-space: nowrap;
        gap: 4px;
        font-weight: bold;

        svg {
            width: 20px;
            height: 20px;
            fill: #000000;

        }
    }

    .dropdown-content a:hover {
        background-color: #f0f0f0;
    }

    .action-dropdown.show .dropdown-content {
        visibility: visible;
        opacity: 1;
        transform: translateY(0);
    }

    .barcode-cell {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .barcode-cell svg {
        width: 45px;
        height: 45px;
        fill: #000000;
    }

    #overlay {
        position: fixed;
        display: none;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    #overlaySoftDrink {
        position: fixed;
        display: none;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    #overlayFreshFood {
        position: fixed;
        display: none;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    #overlaySnackFood {
        position: fixed;
        display: none;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    #overlayStationery {
        position: fixed;
        display: none;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    #overlayAdmin {
        position: fixed;
        display: none;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    .overlay.show {
        opacity: 1;
        pointer-events: auto;
    }

    .side-panel {
        position: fixed;
        top: 0;
        right: -500px;
        width: 500px;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.3);
        transition: right 0.3s ease;
        z-index: 1001;
        display: none;
    }

    .side-panel.show {
        transform: translateX(0);
        pointer-events: auto;
    }

    .side-panel-content {
        height: 100%;
        overflow-y: auto;
        padding: 15px;
        position: relative;
    }

    .side-panel-close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 40px;
        cursor: pointer;
        color: #ffffff;
        border: none;
        transition: transform 0.2s ease, background-color 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
    }

    .side-panel-close-btn:hover {
        background-color: #555555;
        color: #ffffff;
        transition: transform 0.2s ease, background-color 0.2s ease;
        border-radius: 10px;
    }

    body.side-panel-open {
        overflow: hidden;
    }

    #imagePreviewContainer {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    #imagePreview {
        height: 150px;
        object-fit: cover;
        border-radius: 20px;
    }

    #editProductForm {
        max-width: 500px;
        padding: 15px;
        border-radius: 10px;

        p {
            color: #DC143C;
        }
    }

    #editProductForm label {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-weight: 200;
        color: #E1DFE9;
        gap: 4px;
    }

    #editProductForm input[type="text"],
    #editProductForm input[type="number"] {
        width: 100%;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #000000;
        border-radius: 15px;
        font-weight: bold;
        transition: border 0.3s, box-shadow 0.3s;
        background-color: rgba(255, 255, 255, 0.2);
    }

    #editProductForm input[type="text"]:focus,
    #editProductForm input[type="number"]:focus {
        border-color: #DC143C;
        outline: none;
    }

    .form-group-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
    }

    .form-group-row .group-item {
        flex: 1;
    }

    #editProductForm button {
        width: 100%;
        font-size: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 20px;
        background-color: #000000;
        color: #ffffff;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #editProductForm button:hover {
        background-color: #111111;
    }

    #editProductForm button svg {
        width: 28px;
        height: 28px;
        fill: #ffffff;
    }


    /* edit Soft Drink */
    #imagePreviewContainerSoftDrink {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    #imagePreviewSoftDrink {
        height: 150px;
        object-fit: cover;
        border-radius: 20px;
    }

    #editProductFormSoftDrink {
        max-width: 500px;
        padding: 15px;
        border-radius: 10px;
    }

    #editProductFormSoftDrink label {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-weight: 200;
        color: #E1DFE9;
        gap: 4px;
    }

    #editProductFormSoftDrink input[type="text"],
    #editProductFormSoftDrink input[type="number"] {
        width: 100%;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #000000;
        font-weight: bold;
        border-radius: 15px;
        transition: border 0.3s, box-shadow 0.3s;
        background-color: rgba(255, 255, 255, 0.2);
    }

    #editProductFormSoftDrink input[type="text"]:focus,
    #editProductFormSoftDrink input[type="number"]:focus {
        border-color: #DC143C;
        outline: none;
    }

    .form-group-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
    }

    .form-group-row .group-item {
        flex: 1;
    }

    #editProductFormSoftDrink button {
        width: 100%;
        font-size: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 20px;
        background-color: #000000;
        color: #ffffff;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #editProductFormSoftDrink button:hover {
        background-color: #222222;
    }


    #editProductFormSoftDrink button svg {
        width: 28px;
        height: 28px;
        fill: #ffffff;
    }

    /* edit Fresh Food */
    #imagePreviewContainerFreshFood {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    #imagePreviewFreshFood {
        height: 150px;
        object-fit: cover;
        border-radius: 20px;
    }

    #editProductFormFreshFood {
        max-width: 500px;
        padding: 15px;
        border-radius: 10px;

        p {
            color: #DC143C;
        }
    }

    #editProductFormFreshFood label {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-weight: 200;
        color: #E1DFE9;
        gap: 4px;
    }

    #editProductFormFreshFood input[type="text"],
    #editProductFormFreshFood input[type="number"] {
        width: 100%;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #000000;
        font-weight: bold;
        border-radius: 15px;
        transition: border 0.3s, box-shadow 0.3s;
        background-color: rgba(255, 255, 255, 0.2);
    }

    #editProductFormFreshFood input[type="text"]:focus,
    #editProductFormFreshFood input[type="number"]:focus {
        border-color: #DC143C;
        outline: none;
    }

    .form-group-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
    }

    .form-group-row .group-item {
        flex: 1;
    }

    #editProductFormFreshFood button {
        width: 100%;
        font-size: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 20px;
        background-color: #000000;
        color: #ffffff;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #editProductFormFreshFood button:hover {
        background-color: #222222;
    }


    #editProductFormFreshFood button svg {
        width: 28px;
        height: 28px;
        fill: #ffffff;
    }

    /* edit Snack Food */
    #imagePreviewContainerSnackFood {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    #imagePreviewSnackFood {
        height: 150px;
        object-fit: cover;
        border-radius: 20px;
    }

    #editProductFormSnackFood {
        max-width: 500px;
        padding: 15px;
        border-radius: 10px;

        p {
            color: #DC143C;
        }
    }

    #editProductFormSnackFood label {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-weight: 200;
        color: #E1DFE9;
        gap: 4px;
    }

    #editProductFormSnackFood input[type="text"],
    #editProductFormSnackFood input[type="number"] {
        width: 100%;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #000000;
        border-radius: 15px;
        font-weight: bold;
        transition: border 0.3s, box-shadow 0.3s;
        background-color: rgba(255, 255, 255, 0.2);
    }

    #editProductFormSnackFood input[type="text"]:focus,
    #editProductFormSnackFood input[type="number"]:focus {
        border-color: #DC143C;
        outline: none;
    }

    .form-group-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
    }

    .form-group-row .group-item {
        flex: 1;
    }

    #editProductFormSnackFood button {
        width: 100%;
        font-size: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 20px;
        background-color: #000000;
        color: #ffffff;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #editProductFormSnackFood button:hover {
        background-color: #222222;
    }


    #editProductFormSnackFood button svg {
        width: 28px;
        height: 28px;
        fill: #ffffff;
    }

    /* edit Stationery */
    #imagePreviewContainerStationery {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    #imagePreviewStationery {
        height: 150px;
        object-fit: cover;
        border-radius: 20px;
    }

    #editProductFormStationery {
        max-width: 500px;
        padding: 15px;
        border-radius: 10px;

        p {
            color: #DC143C;
        }
    }

    #editProductFormStationery label {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-weight: 200;
        color: #E1DFE9;
        gap: 4px;
    }

    #editProductFormStationery input[type="text"],
    #editProductFormStationery input[type="number"] {
        width: 100%;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #000000;
        font-weight: bold;
        border-radius: 15px;
        transition: border 0.3s, box-shadow 0.3s;
        background-color: rgba(255, 255, 255, 0.2);
    }

    #editProductFormStationery input[type="text"]:focus,
    #editProductFormStationery input[type="number"]:focus {
        border-color: #DC143C;
        outline: none;
    }

    .form-group-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
    }

    .form-group-row .group-item {
        flex: 1;
    }

    #editProductFormStationery button {
        width: 100%;
        font-size: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 20px;
        background-color: #000000;
        color: #ffffff;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #editProductFormStationery button:hover {
        background-color: #222222;
    }


    #editProductFormStationery button svg {
        width: 28px;
        height: 28px;
        fill: #ffffff;
    }

    #imagePreviewContainerAdmin {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    #imagePreviewAdmin {
        height: 150px;
        object-fit: cover;
        border-radius: 20px;
    }

    #editAdminForm {
        max-width: 500px;
        padding: 15px;
        border-radius: 10px;
    }

    #editAdminForm label {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-weight: 200;
        color: #E1DFE9;
        gap: 4px;
    }

    #editAdminForm input[type="text"],
    #editAdminForm input[type="email"],
    #editAdminForm input[type="password"],
    #editAdminForm input[type="file"] {
        width: 100%;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #000000;
        font-weight: bold;
        border-radius: 15px;
        transition: border 0.3s, box-shadow 0.3s;
        background-color: rgba(255, 255, 255, 0.2);
    }

    #editAdminForm input[type="text"]:focus,
    #editAdminForm input[type="email"]:focus,
    #editAdminForm input[type="password"]:focus,
    #editAdminForm input[type="file"]:focus {
        border-color: #DC143C;
        outline: none;
    }

    #editAdminForm button {
        width: 100%;
        font-size: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 20px;
        background-color: #000000;
        color: #ffffff;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #editAdminForm button:hover {
        background-color: #222222;
    }

    #editAdminForm button svg {
        width: 28px;
        height: 28px;
        fill: #ffffff;
    }


    .excel-grid-upload {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
        margin-top: 20px;
    }

    @media (max-width: 1200px) {
        .excel-grid-upload {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 900px) {
        .excel-grid-upload {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .excel-grid-upload {
            grid-template-columns: 1fr;
        }
    }

    .excel-upload-form {
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 15px;
        border-radius: 20px;
    }

    .excel-type-title {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        gap: 4px;
        font-size: 16px;
        margin-bottom: 16px;
    }

    .excel-upload-group {
        margin-bottom: 15px;
    }

    .excel-upload-input {
        width: 100%;
        padding: 8px;
        font-size: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12.5px;
        display: block !important;
        z-index: 999 !important;
        cursor: pointer;
    }

    .file-name-text {
        margin-top: 15px;
        font-size: 10px;
        color: #555555;
        text-align: center;
    }

    .excel-upload-button {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        background-color: #8739F9;
        color: #ffffff;
        font-size: 16px;
        font-weight: 500;
        padding: 12px 24px;
        border: none;
        border-radius: 15px;
        cursor: pointer;
        transition: background-color 0.25s ease, transform 0.2s ease;
    }

    .excel-upload-button:hover {
        background-color: #5419B5;
    }

    #password-checklist {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        list-style: none;
        padding: 0;
        margin-bottom: 15px;
    }

    #password-checklist li {
        display: flex;
        text-align: center;
        align-items: center;
        justify-content: center;
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 12px;
        border-radius: 15px;
        font-size: 12px;
        color: #777;
        gap: 4px;
    }

    #password-checklist .icon {
        display: inline-block;
        width: 20px;
        text-align: center;
        font-weight: bold;
        font-size: 16px;
    }

    #password-checklist li.valid {
        color: #4CAF50;
    }

    #password-checklist li.valid .icon {
        color: #4CAF50;
        content: "✔";
    }

    #password-checklist li.invalid {
        color: #DC143C;
    }

    #password-checklist li.invalid .icon {
        color: #DC143C;
    }

    #upload-img-admin {
        display: none;
    }

    .file-info-wrapper {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-left: 36px;
        flex-wrap: nowrap;
    }

    #file-name {
        color: #ffffff;
        font-size: 16px;
        font-weight: 500;
        padding: 4px 10px;
        border-radius: 6px;
        max-width: 220px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex-shrink: 1;
    }

    .file-limit-note {
        font-weight: 500;
        font-size: 14px;
        color: #DC143C;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .custom-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10000;
    }

    .custom-modal.hidden {
        display: none;
    }

    .update-dashboard-title {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
    }

    .update-filters {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .filter-input {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        flex: 1;
        min-width: 180px;
    }

    .action-edit {
        color: #FFA500;
        font-weight: bold;
    }

    .action-delete {
        color: #FF4B4B;
        font-weight: bold;
    }

    .action-add {
        color: #4CAF50;
        font-weight: bold;
    }

    .input-clear-wrapper {
        position: relative;
        width: 100%;
    }

    #productImage {
        width: 100%;
        padding-right: 40px;
        box-sizing: border-box;
    }

    .clear-btn {
        width: 40px;
        height: 40px;
        position: absolute;
        right: 4px;
        top: 50%;
        transform: translateY(-50%);
        background: #000000;
        border: none;
        cursor: pointer;
        color: #ffffff;
        display: none;
        padding: 5px;
        border-radius: 10px;
        line-height: 1;

        span {
            font-size: 30px;
            margin: 0;
        }
    }

    .clear-btn:hover {
        color: #FF4B4B;
    }
</style>

<body>
    <nav class="navbar">
        <!-- แฮมเบอร์เกอร์ -->
        <div class="hamburger" onclick="toggleSidebar()">
            <span class="open-icon">
                <svg xmlns="http://www.w3.org/2000/svg" height="40px" width="40px" viewBox="0 -960 960 960" fill="#ffffff">
                    <path d="M120-240v-80h520v80H120Zm664-40L584-480l200-200 56 56-144 144 144 144-56 56ZM120-440v-80h400v80H120Zm0-200v-80h520v80H120Z" />
                </svg>
            </span>
            <span class="close-icon" style="display: none;">
                <svg xmlns="http://www.w3.org/2000/svg" height="40px" width="40px" viewBox="0 -960 960 960" fill="#ffffff">
                    <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
                </svg>
            </span>
        </div>

        <!-- โลโก้และชื่อ -->
        <div class="logo-name">
            <img src="\sci-next\img\sci-next.svg" alt="Logo" class="logo">
            <span class="site-name">SCi_ADMIN</span>
        </div>
    </nav>

    <div id="tabLoadingBar"></div>

    <div class="sidebar">

        <!-- รายการหลัก -->
        <div class="main-tabs">
            <h3>รายการหลัก</h3>

            <div class="tab" data-tab="graph">
                <span class="material-icons">area_chart</span> แดชบอร์ดสถิติ
            </div>

            <div class="tab" id="orderTab" data-tab="order">
                <span class="material-icons">shopping_cart</span>
                <span class="badge">99+</span> <!-- ตัวเลขแจ้งเตือน -->
                รายการขาย
            </div>

            <div class="tab" data-tab="update_product">
                <span class="material-icons">analytics</span> ประวัติการอัปเดต
            </div>

        </div>
        <hr class="tab-divider">

        <!-- รายการอัพโหลด -->
        <div class="main-tabs-upload">
            <h3>รายการอัพโหลด</h3>
            <div class="tab" data-tab="admin_signup">
                <span class="material-icons">group_add</span> สมัครแอดมิน
            </div>

            <div class="tab" data-tab="employee_signup">
                <span class="material-icons">group_add</span> สมัครพนักงาน
            </div>

            <div class="tab" data-tab="upload_prodect">
                <span class="material-icons">add_shopping_cart</span> อัพโหลดสินค้า
            </div>

            <div class="tab" data-tab="upload_file_excal">
                <span class="material-icons">upload_file</span> อัปโหลดไฟล์สินค้า
            </div>

            <button type="button" is="toggle-button" class="collapsible-toggle text-strong" aria-controls="menu" aria-expanded="false">
                <span class="material-icons">checklist</span> ตรวจสอบสินค้า
                <svg focusable="false" width="12" height="8" class="icon icon--chevron icon--inline" viewBox="0 0 12 8">
                    <path fill="none" d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="2"></path>
                </svg>
            </button>

            <div id="menu" class="menu">
                <div class="tab" data-tab="food_bank_check">
                    <span class="material-icons">food_bank</span> หมวดแห้ง
                </div>
                <div class="tab" data-tab="local_drink_check">
                    <span class="material-icons">local_drink</span> หมวดเครื่องดื่ม
                </div>
                <div class="tab" data-tab="fastfood_check">
                    <span class="material-icons">fastfood</span> หมวดแช่แข็ง
                </div>
                <div class="tab" data-tab="snack_check">
                    <span class="material-icons">cookie</span> หมวดขนม
                </div>
                <div class="tab" data-tab="stationery_check">
                    <span class="material-icons">create</span> หมวดเครื่องเขียน
                </div>
            </div>
        </div>
        <hr class="tab-divider">

        <!-- รายการสินค้า -->
        <div class="main-tabs-products">
            <h3>รายการสินค้า</h3>
            <div class="tab" data-tab="dried_food">
                <span class="material-icons">food_bank</span> ประเภทแห้ง
            </div>
            <div class="tab" data-tab="soft_drink">
                <span class="material-icons">local_drink</span> ประเภทเครื่องดื่ม
            </div>
            <div class="tab" data-tab="fresh_food">
                <span class="material-icons">fastfood</span> ประเภทแช่แข็ง
            </div>
            <div class="tab" data-tab="snack_food">
                <span class="material-icons">cookie</span> ประเภทขนม
            </div>
            <div class="tab" data-tab="stationery">
                <span class="material-icons">create</span> ประเภทเครื่องเขียน
            </div>
        </div>
        <hr class="tab-divider">


        <div class="main-tabs-products">
            <h3>รายการจัดการ</h3>
            <div class="tab" data-tab="sci_admin">
                <span class="material-icons">manage_accounts</span> จัดการแอดมิน
            </div>
            <div class="tab" data-tab="employee">
                <span class="material-icons">manage_accounts</span> จัดการพนักงาน
            </div>
        </div>
        <hr class="tab-divider">

        <div class="tab account" data-tab="account">
            <span class="material-icons">account_circle</span> โปรไฟล์แอดมิน
        </div>
        <a class="tab logout" href="#" onclick="showLogoutModal(event)">
            <span class="material-icons">logout</span> ออกจากระบบ
        </a>
    </div>

    <!-- ^^ ส่วนของ Tabs ^^ -->

    <!-- แสดงหน้าของ Tabs -->
    <!-- แสดงข้อมูลสถิติ -->
    <div id="order" class="content">
        <div>
            <div class="header-container">
                <h3 class="h-text-upload">
                    ตารางรายการขายสินค้า
                </h3>
                <div class="order-date-wrapper">
                    <label for="order-date-picker" class="order-date-label">
                        เลือกวันที่
                        <input type="date" id="order-date-picker" class="order-date-input" />
                    </label>
                </div>
            </div>
            <div id="order-container"></div>
        </div>
    </div>

    <div id="update_product" class="content">
        <!-- ส่วนหัว -->
        <h2 class="update-dashboard-title">แดชบอร์ดอัปเดตสินค้า</h2>

        <!-- ตัวกรอง -->
        <div class="update-filters">
            <input type="text" placeholder="ค้นหาชื่อสินค้า..." class="filter-input">
            <input type="text" placeholder="ค้นหาผู้ใช้งาน..." class="filter-input">
            <select class="filter-input">
                <option value="">ประเภทการกระทำ</option>
                <option value="add">เพิ่ม</option>
                <option value="update">แก้ไข</option>
                <option value="delete">ลบ</option>
            </select>
        </div>

        <!-- ตารางประวัติ -->
        <table class="update-history-table">
            <thead>
                <tr>
                    <th>วันที่เวลา</th>
                    <th>ผู้ดำเนินการ</th>
                    <th>ชื่อสินค้า</th>
                    <th>การกระทำ</th>
                    <th>รายละเอียด</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2025-05-17 18:42</td>
                    <td>admin01</td>
                    <td>โค้ก 1.25L</td>
                    <td class="action-edit">แก้ไข</td>
                    <td>ราคาจาก 20 → 22 บาท</td>
                </tr>
                <tr>
                    <td>2025-05-17 19:10</td>
                    <td>superadmin</td>
                    <td>ปลากระป๋องโรซ่า</td>
                    <td class="action-delete">ลบ</td>
                    <td>ลบสินค้าถาวร</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- สรุปยอดขายเป็นตัวเลข -->
    <div id="graph" class="content">
        <!-- สรุปยอดขายรายวัน -->
        <div class="parent">
            <div class="div1 stat-card" style="--accent: #37B9F1;">
                <div class="stat-header">
                    <span class="material-icons">today</span>
                    <div class="info">
                        <h5>รายวัน</h5>
                    </div>
                    <p>900<span class="unit">บาท</span></p>
                </div>
            </div>

            <!-- สรุปยอดขายรายเดือน -->
            <div class="div2 stat-card" style="--accent: #8739F9;">
                <div class="stat-header">
                    <span class="material-icons">calendar_month</span>
                    <div class="info">
                        <h5>รายเดือน</h5>
                    </div>
                    <p>9000<span class="unit">บาท</span></p>
                </div>
            </div>

            <!-- รายปี -->
            <div class="div3 stat-card" style="--accent: #BFA3F9;">
                <div class="stat-header">
                    <span class="material-icons">equalizer</span>
                    <div class="info">
                        <h5>รายปี</h5>
                    </div>
                    <p>90000<span class="unit">บาท</span></p>
                </div>
            </div>

            <!-- จำนวนคำสั่งซื้อ -->
            <div class="div4 stat-card" style="--accent: #565360;">
                <div class="stat-header">
                    <span class="material-icons">shopping_bag</span>
                    <div class="info">
                        <h5>คำสั่งซื้อ</h5>
                    </div>
                    <p>99<span class="unit">รายการ</span></p>
                </div>
            </div>

            <!-- กราฟซ้าย -->
            <div class="div9 chart-container">
                <h4>กราฟภาพรวมยอดขาย</h4>
                <canvas id="mainSalesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- ฟอร์มสมัครสมาชิก admin พร้อมแอททริบิวต์ autocomplete -->

    <div id="admin_signup" class="content">

        <div class="upload-container">
            <div class="product-preview-box">
                <div class="product-preview-image">
                    <img id="previewImageAdmin" src="/sci-next/img/product_food1.png" alt="รูปภาพแอดมิน">
                </div>
                <div class="product-details">
                    <div class="detail-group">
                        <span class="material-icons">person</span>
                        <div>
                            <span id="previewFirstNameAdmin"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <span class="material-icons">person_outline</span>
                        <div>
                            <span id="previewLastNameAdmin"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <span class="material-icons">email</span>
                        <div>
                            <span id="previewGmailAdmin"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <span class="material-icons">lock</span>
                        <div>
                            <span id="previewPasswordAdmin"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <span class="material-icons">lock_open</span>
                        <div>
                            <span id="previewConfirmPasswordAdmin"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-container">

                <h1 class="h-text-upload">เพิ่มแอดมินใหม่
                    <p>Create New Admin Account</p>
                </h1>

                <form class="form-upload" id="adminSignupForm" method="POST" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="upload-img-admin">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M440-440v-160H280v-80h160v-160h80v160h160v80H520v160h-80Zm40 280q-16 0-28-12t-12-28q0-16 12-28t28-12q16 0 28 12t12 28q0 16-12 28t-28 12Zm-320 80q-33 0-56.5-23.5T80-160v-480q0-33 23.5-56.5T160-720h640q33 0 56.5 23.5T880-640v480q0 33-23.5 56.5T800-80H160Zm0-80h640v-480H160v480Z" />
                            </svg>
                        </label>

                        <label for="upload-img-admin" class="custom-file-upload">เลือกไฟล์รูปภาพ</label>
                        <input type="file" id="upload-img-admin" accept="image/*" required>

                        <!-- แสดงชื่อไฟล์ -->
                        <div class="file-info-wrapper">
                            <span id="file-name"></span>
                            <small class="file-limit-note">* ไม่เกิน 1MB</small>
                        </div>
                    </div>

                    <!-- แถวสำหรับชื่อและนามสกุล -->
                    <div class="from-container-stock">
                        <div class="form-group">
                            <label for="firstName-admin"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                    <path d="M80-80v-120q0-33 23.5-56.5T160-280h640q33 0 56.5 23.5T880-200v120H80Zm80-80h640v-40H160v40Zm40-180v-460q0-33 23.5-56.5T280-880h400q33 0 56.5 23.5T760-800v460h-80v-460H280v460h-80Zm120-60h23q44 0 70.5-44T440-560q0-72-26.5-116T343-720h-23v320Zm240-80q33 0 56.5-23.5T640-560q0-33-23.5-56.5T560-640q-33 0-56.5 23.5T480-560q0 33 23.5 56.5T560-480Zm-80 320Zm0-410Z" />
                                </svg></label>
                            <input type="text" id="firstName-admin" name="firstName" placeholder="กรอกชื่อ" required autocomplete="given-name">
                        </div>
                        <div class="form-group">
                            <label for="lastName-admin">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                    <path d="M481-781q106 0 200 45.5T838-604q7 9 4.5 16t-8.5 12q-6 5-14 4.5t-14-8.5q-55-78-141.5-119.5T481-741q-97 0-182 41.5T158-580q-6 9-14 10t-14-4q-7-5-8.5-12.5T126-602q62-85 155.5-132T481-781Zm0 94q135 0 232 90t97 223q0 50-35.5 83.5T688-257q-51 0-87.5-33.5T564-374q0-33-24.5-55.5T481-452q-34 0-58.5 22.5T398-374q0 97 57.5 162T604-121q9 3 12 10t1 15q-2 7-8 12t-15 3q-104-26-170-103.5T358-374q0-50 36-84t87-34q51 0 87 34t36 84q0 33 25 55.5t59 22.5q34 0 58-22.5t24-55.5q0-116-85-195t-203-79q-118 0-203 79t-85 194q0 24 4.5 60t21.5 84q3 9-.5 16T208-205q-8 3-15.5-.5T182-217q-15-39-21.5-77.5T154-374q0-133 96.5-223T481-687Zm0-192q64 0 125 15.5T724-819q9 5 10.5 12t-1.5 14q-3 7-10 11t-17-1q-53-27-109.5-41.5T481-839q-58 0-114 13.5T260-783q-8 5-16 2.5T232-791q-4-8-2-14.5t10-11.5q56-30 117-46t124-16Zm0 289q93 0 160 62.5T708-374q0 9-5.5 14.5T688-354q-8 0-14-5.5t-6-14.5q0-75-55.5-125.5T481-550q-76 0-130.5 50.5T296-374q0 81 28 137.5T406-123q6 6 6 14t-6 14q-6 6-14 6t-14-6q-59-62-90.5-126.5T256-374q0-91 66-153.5T481-590Zm-1 196q9 0 14.5 6t5.5 14q0 75 54 123t126 48q6 0 17-1t23-3q9-2 15.5 2.5T744-191q2 8-3 14t-13 8q-18 5-31.5 5.5t-16.5.5q-89 0-154.5-60T460-374q0-8 5.5-14t14.5-6Z" />
                                </svg></label>
                            <input type="text" id="lastName-admin" name="lastName" placeholder="กรอกนามสกุล" required autocomplete="family-name">
                        </div>
                    </div>

                    <hr class="tab-divider-admin">

                    <div class="form-group">
                        <label for="gmail-admin">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M560-440h200v-80H560v80Zm0-120h200v-80H560v80ZM200-320h320v-22q0-45-44-71.5T360-440q-72 0-116 26.5T200-342v22Zm160-160q33 0 56.5-23.5T440-560q0-33-23.5-56.5T360-640q-33 0-56.5 23.5T280-560q0 33 23.5 56.5T360-480ZM160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80h640v-480H160v480Zm0 0v-480 480Z" />
                            </svg>
                        </label>
                        <input type="gmail" id="gmail-admin" name="gmail" placeholder="กรอก Gmail ผู้ใช้งาน" required autocomplete="email">
                    </div>


                    <div class="form-group">
                        <label for="password-admin">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M80-200v-80h800v80H80Zm46-242-52-30 34-60H40v-60h68l-34-58 52-30 34 58 34-58 52 30-34 58h68v60h-68l34 60-52 30-34-60-34 60Zm320 0-52-30 34-60h-68v-60h68l-34-58 52-30 34 58 34-58 52 30-34 58h68v60h-68l34 60-52 30-34-60-34 60Zm320 0-52-30 34-60h-68v-60h68l-34-58 52-30 34 58 34-58 52 30-34 58h68v60h-68l34 60-52 30-34-60-34 60Z" />
                            </svg>
                        </label>
                        <input type="password" id="password-admin" name="password" placeholder="กรอกรหัสผ่าน" required autocomplete="new-password">
                    </div>

                    <ul id="password-checklist">
                        <li id="length"><span class="icon">✖</span> อย่างน้อย 8 ตัวอักษร</li>
                        <li id="uppercase"><span class="icon">✖</span> มีตัวพิมพ์ใหญ่ (A-Z)</li>
                        <li id="number"><span class="icon">✖</span> มีตัวเลข (0-9)</li>
                        <li id="special"><span class="icon">✖</span> มีอักษรพิเศษ (!@#$...)</li>
                    </ul>

                    <div class="form-group">
                        <label for="confirmPassword-admin">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M480-480Zm0 400q-139-35-229.5-159.5T160-516v-244l320-120 320 120v262q0 9-1 19h-81q1-10 1.5-19t.5-18v-189l-240-90-240 90v189q0 121 68 220t172 132v84Zm200 0v-120H560v-80h120v-120h80v120h120v80H760v120h-80ZM420-360h120l-23-129q20-10 40-26.5t34-39.5l-69-51-63 71-55-67-70 55 86 121q-14 52-44 77t-54 25q-14 0-23-8.5t-9-19.5q0-13 13-22t31-9q14 0 26.5 4t22.5 12l35-61Zm0-160Zm0-160Z" />
                            </svg>
                        </label>
                        <input type="password" id="confirmPassword-admin" name="confirmPassword" placeholder="กรอกยืนยันรหัสผ่าน" required autocomplete="new-password">
                    </div>

                    <button type="submit" id="submitAdminBtn" class="btn-upload">สมัครแอดมิน</button>
                </form>
            </div>
        </div>
    </div>

    <!-- ฟอร์มสมัครสมาชิก employee พร้อมแอททริบิวต์ autocomplete -->
    <div id="employee_signup" class="content">

        <div class="upload-container">
            <div class="product-preview-box">
                <div class="product-preview-image">
                    <img id="previewImageEmployee" src="/sci-next/img/product_food1.png" alt="รูปภาพพนักงาน">
                </div>
                <div class="product-details">
                    <div class="detail-group">
                        <span class="material-icons">person</span>
                        <div>
                            <span id="previewFirstNameEmployee"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <span class="material-icons">person_outline</span>
                        <div>
                            <span id="previewLastNameEmployee"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <span class="material-icons">email</span>
                        <div>
                            <span id="previewGmailEmployee"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <span class="material-icons">lock</span>
                        <div>
                            <span id="previewPasswordEmployee"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <span class="material-icons">lock_open</span>
                        <div>
                            <span id="previewConfirmPasswordEmployee"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-container">

                <h2 class="h-text-upload">เพิ่มพนักงานใหม่
                    <p>Create New Employee Account</p>
                </h2>

                <form class="form-upload" id="employeeSignupForm" method="POST" enctype="multipart/form-data">

                    <div class="form-group"><label for="upload-img-employee">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M440-440v-160H280v-80h160v-160h80v160h160v80H520v160h-80Zm40 280q-16 0-28-12t-12-28q0-16 12-28t28-12q16 0 28 12t12 28q0 16-12 28t-28 12Zm-320 80q-33 0-56.5-23.5T80-160v-480q0-33 23.5-56.5T160-720h640q33 0 56.5 23.5T880-640v480q0 33-23.5 56.5T800-80H160Zm0-80h640v-480H160v480Z" />
                            </svg>
                        </label>

                        <label for="upload-img-employee" class="custom-file-upload">เลือกไฟล์รูปภาพ</label>
                        <input type="file" id="upload-img-employee" accept="image/*" required>

                        <div class="file-info-wrapper">
                            <span id="file-name"></span>
                            <small class="file-limit-note">* ไม่เกิน 1MB</small>
                        </div>
                    </div>

                    <div class="from-container-stock">
                        <div class="form-group">
                            <label for="firstName-employee">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                    <path d="M80-80v-120q0-33 23.5-56.5T160-280h640q33 0 56.5 23.5T880-200v120H80Zm80-80h640v-40H160v40Zm40-180v-460q0-33 23.5-56.5T280-880h400q33 0 56.5 23.5T760-800v460h-80v-460H280v460h-80Zm120-60h23q44 0 70.5-44T440-560q0-72-26.5-116T343-720h-23v320Zm240-80q33 0 56.5-23.5T640-560q0-33-23.5-56.5T560-640q-33 0-56.5 23.5T480-560q0 33 23.5 56.5T560-480Zm-80 320Zm0-410Z" />
                                </svg>
                            </label>
                            <input type="text" id="firstName-employee" name="firstName" placeholder="กรอกชื่อ" required autocomplete="given-name">
                        </div>
                        <div class="form-group">
                            <label for="lastName-employee">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                    <path d="M481-781q106 0 200 45.5T838-604q7 9 4.5 16t-8.5 12q-6 5-14 4.5t-14-8.5q-55-78-141.5-119.5T481-741q-97 0-182 41.5T158-580q-6 9-14 10t-14-4q-7-5-8.5-12.5T126-602q62-85 155.5-132T481-781Zm0 94q135 0 232 90t97 223q0 50-35.5 83.5T688-257q-51 0-87.5-33.5T564-374q0-33-24.5-55.5T481-452q-34 0-58.5 22.5T398-374q0 97 57.5 162T604-121q9 3 12 10t1 15q-2 7-8 12t-15 3q-104-26-170-103.5T358-374q0-50 36-84t87-34q51 0 87 34t36 84q0 33 25 55.5t59 22.5q34 0 58-22.5t24-55.5q0-116-85-195t-203-79q-118 0-203 79t-85 194q0 24 4.5 60t21.5 84q3 9-.5 16T208-205q-8 3-15.5-.5T182-217q-15-39-21.5-77.5T154-374q0-133 96.5-223T481-687Zm0-192q64 0 125 15.5T724-819q9 5 10.5 12t-1.5 14q-3 7-10 11t-17-1q-53-27-109.5-41.5T481-839q-58 0-114 13.5T260-783q-8 5-16 2.5T232-791q-4-8-2-14.5t10-11.5q56-30 117-46t124-16Zm0 289q93 0 160 62.5T708-374q0 9-5.5 14.5T688-354q-8 0-14-5.5t-6-14.5q0-75-55.5-125.5T481-550q-76 0-130.5 50.5T296-374q0 81 28 137.5T406-123q6 6 6 14t-6 14q-6 6-14 6t-14-6q-59-62-90.5-126.5T256-374q0-91 66-153.5T481-590Zm-1 196q9 0 14.5 6t5.5 14q0 75 54 123t126 48q6 0 17-1t23-3q9-2 15.5 2.5T744-191q2 8-3 14t-13 8q-18 5-31.5 5.5t-16.5.5q-89 0-154.5-60T460-374q0-8 5.5-14t14.5-6Z" />
                                </svg>
                            </label>
                            <input type="text" id="lastName-employee" name="lastName" placeholder="กรอกนามสกุล" required autocomplete="family-name">
                        </div>
                    </div>

                    <hr class="tab-divider-admin">

                    <div class="form-group">
                        <label for="gmail-employee">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M560-440h200v-80H560v80Zm0-120h200v-80H560v80ZM200-320h320v-22q0-45-44-71.5T360-440q-72 0-116 26.5T200-342v22Zm160-160q33 0 56.5-23.5T440-560q0-33-23.5-56.5T360-640q-33 0-56.5 23.5T280-560q0 33 23.5 56.5T360-480ZM160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80h640v-480H160v480Zm0 0v-480 480Z" />
                            </svg>
                        </label>
                        <input type="email" id="gmail-employee" name="gmail" placeholder="กรอก Gmail พนักงาน" required autocomplete="email">
                    </div>

                    <div class="form-group">
                        <label for="password-employee">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M80-200v-80h800v80H80Zm46-242-52-30 34-60H40v-60h68l-34-58 52-30 34 58 34-58 52 30-34 58h68v60h-68l34 60-52 30-34-60-34 60Zm320 0-52-30 34-60h-68v-60h68l-34-58 52-30 34 58 34-58 52 30-34 58h68v60h-68l34 60-52 30-34-60-34 60Zm320 0-52-30 34-60h-68v-60h68l-34-58 52-30 34 58 34-58 52 30-34 58h68v60h-68l34 60-52 30-34-60-34 60Z" />
                            </svg>
                        </label>
                        <input type="password" id="password-employee" name="password" placeholder="กรอกรหัสผ่าน" required autocomplete="new-password">
                    </div>

                    <ul id="password-checklist">
                        <li id="length"><span class="icon">✖</span> อย่างน้อย 8 ตัวอักษร</li>
                        <li id="uppercase"><span class="icon">✖</span> มีตัวพิมพ์ใหญ่ (A-Z)</li>
                        <li id="number"><span class="icon">✖</span> มีตัวเลข (0-9)</li>
                        <li id="special"><span class="icon">✖</span> มีอักษรพิเศษ (!@#$...)</li>
                    </ul>

                    <div class="form-group">
                        <label for="confirmPassword-employee">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M480-480Zm0 400q-139-35-229.5-159.5T160-516v-244l320-120 320 120v262q0 9-1 19h-81q1-10 1.5-19t.5-18v-189l-240-90-240 90v189q0 121 68 220t172 132v84Zm200 0v-120H560v-80h120v-120h80v120h120v80H760v120h-80ZM420-360h120l-23-129q20-10 40-26.5t34-39.5l-69-51-63 71-55-67-70 55 86 121q-14 52-44 77t-54 25q-14 0-23-8.5t-9-19.5q0-13 13-22t31-9q14 0 26.5 4t22.5 12l35-61Zm0-160Zm0-160Z" />
                            </svg>
                        </label>
                        <input type="password" id="confirmPassword-employee" name="confirmPassword" placeholder="กรอกยืนยันรหัสผ่าน" required autocomplete="new-password">
                    </div>

                    <button type="submit" id="submitEmployeeBtn" class="btn-upload">
                        สมัครพนักงาน
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- ฟอร์มอัปโหลดสินค้า -->
    <div id="upload_prodect" class="content">

        <div class="upload-container">
            <div class="product-preview-box">
                <div class="product-preview-image">
                    <img id="previewImageProduct" src="/sci-next/img/product_food1.png" alt="รูปภาพสินค้า">
                </div>

                <div class="product-details">
                    <div class="detail-group">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                            <path d="M856-390 570-104q-12 12-27 18t-30 6q-15 0-30-6t-27-18L103-457q-11-11-17-25.5T80-513v-287q0-33 23.5-56.5T160-880h287q16 0 31 6.5t26 17.5l352 353q12 12 17.5 27t5.5 30q0 15-5.5 29.5T856-390ZM260-640q25 0 42.5-17.5T320-700q0-25-17.5-42.5T260-760q-25 0-42.5 17.5T200-700q0 25 17.5 42.5T260-640Z" />
                        </svg>
                        <div>
                            <span class="text" id="previewNameProduct"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                            <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                        </svg>
                        <div>
                            <span id="previewBarcodeProduct"></span>
                        </div>
                    </div>

                    <div class="detail-inline">
                        <p>
                            <span class="material-icons">paid</span>
                            <span id="previewPriceProduct"></span>
                        </p>
                        <p>
                            <span class="material-icons">money</span>
                            <span id="previewCostProduct"></span>
                        </p>
                    </div>

                    <div class="detail-group">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                            <path d="M80-120v-560l400-160 400 160v560H640v-320H320v320H80Zm280 0v-80h80v80h-80Zm80-120v-80h80v80h-80Zm80 120v-80h80v80h-80Z" />
                        </svg>
                        <div>
                            <span id="previewStockProduct"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                            <path d="M320-440v-287L217-624l-57-56 200-200 200 200-57 56-103-103v287h-80ZM600-80 400-280l57-56 103 103v-287h80v287l103-103 57 56L600-80Z" />
                        </svg>
                        <div>
                            <span id="previewReorderLevelProduct"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-container">
                <div class="form-header-upload">
                    <h3 class="h-text-upload">เพิ่มสินค้าใหม่</h3>
                    <p class="p-text-upload">*โปรดเลือกหมวดหมู่ก่อนดำเนินการเพิ่มสินค้า*</p>
                    <span class="material-icons tooltip" data-tooltip="เพราะระบบต้องรู้ว่าสินค้าใหม่จะถูกจัดไว้ในหมวดหมู่ใด หากไม่เลือกจะไม่สามารถดำเนินการต่อได้">library_books</span>
                </div>

                <form class="form-upload" id="uploadForm" method="POST" action="" enctype="multipart/form-data" onsubmit="return handleFormSubmit(event)">
                    <div class="category-buttons">
                        <button type="button" class="category-btn" data-category="dried_food" onclick="selectCategory(event, 'dried_food')">
                            <span class="material-icons">food_bank</span>
                            ของแห้ง
                        </button>

                        <button type="button" class="category-btn" data-category="soft_drink" onclick="selectCategory(event, 'soft_drink')">
                            <span class="material-icons">local_drink</span>
                            เครื่องดื่ม
                        </button>

                        <button type="button" class="category-btn" data-category="fresh_food" onclick="selectCategory(event, 'fresh_food')">
                            <span class="material-icons">fastfood</span>
                            ของแช่แข็ง
                        </button>

                        <button type="button" class="category-btn" data-category="snack" onclick="selectCategory(event, 'snack')">
                            <span class="material-icons">cookie</span>
                            ขนม
                        </button>

                        <button type="button" class="category-btn" data-category="stationery" onclick="selectCategory(event, 'stationery')">
                            <span class="material-icons">create</span>
                            เครื่องเขียน
                        </button>

                    </div>

                    <input type="hidden" id="productCategory" name="productCategory">

                    <hr class="tab-divider-category">

                    <div class="form-group">
                        <label for="productImage">
                            <span class="material-icons" style="vertical-align: middle;">add_photo_alternate</span>
                        </label>
                        <div class="input-clear-wrapper">
                            <input type="text" id="productImage" name="productImage" placeholder="กรอก URL ของรูปภาพ" required autocomplete="on"
                                oninput="toggleClearBtn()">
                            <button type="button" class="clear-btn" data-tooltip="ลบ URL" onclick="clearInput()"><span class="material-icons">close</span>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="productName">
                            <span class="material-icons">sell</span>
                        </label>
                        <input type="text" id="productName" name="productName" placeholder="กรอกชื่อสินค้า" required>
                    </div>

                    <div class="form-group">
                        <label for="barcode">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                            </svg>
                        </label>
                        <input type="text" id="barcode" name="barcode" placeholder="กรอกบาร์โค้ด" required>
                    </div>

                    <div class="form-container-product">
                        <div class="form-group">
                            <label for="productPrice">
                                <span class="material-icons">paid</span>
                            </label>
                            <input type="number" id="productPrice" name="productPrice" placeholder="กรอกราคาขาย" required>
                        </div>

                        <div class="form-group">
                            <label for="productCost">
                                <span class="material-icons">money</span>
                            </label>
                            <input type="number" id="productCost" name="productCost" placeholder="กรอกราคาต้นทุน" required>
                        </div>
                    </div>


                    <div class="from-container-stock">
                        <div class="form-group">
                            <label for="productStock">
                                <span class="material-icons">warehouse</span>
                            </label>
                            <input type="number" id="productStock" name="productStock" placeholder="กรอกจำนวนสต็อก" required>
                        </div>

                        <div class="form-group">
                            <label for="productReorderLevel">
                                <span class="material-icons">swap_vert</span>
                            </label>
                            <input type="number" id="productReorderLevel" name="productReorderLevel" placeholder="กรอกจำนวนสต็อกต่ำสุด" required>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn-upload">
                            อัปโหลดสินค้า
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ฟอร์มอัปโหลดไฟล์ Excel -->

    <div id="upload_file_excal" class="content">
        <h3 class="h-text-upload">
            เพิ่มสินค้าใหม่ผ่านไฟล์ Excel
            <p>Add Products by Uploading File</p>
        </h3>

        <div class="excel-grid-upload">
            <!-- ประเภทแห้ง -->
            <form action="../../product/upload_product/upload_product_excal/upload_excel_dried.php" method="POST" enctype="multipart/form-data" class="excel-upload-form">
                <h4 class="excel-type-title"><span class="material-icons">food_bank</span>ประเภทแห้ง
                </h4>
                <div class="excel-upload-group">
                    <input type="file" name="excel_file" class="excel-upload-input" accept=".xlsx, .xls" required onchange="showFileName(this)">
                    <div class="file-name-text">ยังไม่ได้เลือกไฟล์</div>
                </div>
                <div class="excel-upload-group">
                    <button type="submit" class="excel-upload-button">อัปโหลดไฟล์</button>
                </div>
            </form>

            <!-- ประเภทเครื่องดื่ม -->
            <form action="../../product/upload_product/upload_product_excal/upload_excel_drink.php" method="POST" enctype="multipart/form-data" class="excel-upload-form">
                <h4 class="excel-type-title"><span class="material-icons">local_drink</span>ประเภทเครื่องดื่ม
                </h4>
                <div class="excel-upload-group">
                    <input type="file" name="excel_file" class="excel-upload-input" accept=".xlsx, .xls" required onchange="showFileName(this)">
                    <div class="file-name-text">ยังไม่ได้เลือกไฟล์</div>
                </div>
                <div class="excel-upload-group">
                    <button type="submit" class="excel-upload-button">อัปโหลดไฟล์</button>
                </div>
            </form>

            <!-- ประเภทแช่แข็ง -->
            <form action="../../product/upload_product/upload_product_excal/upload_excel_fresh.php" method="POST" enctype="multipart/form-data" class="excel-upload-form">
                <h4 class="excel-type-title"><span class="material-icons">fastfood</span>ประเภทแช่แข็ง
                </h4>
                <div class="excel-upload-group">
                    <input type="file" name="excel_file" class="excel-upload-input" accept=".xlsx, .xls" required onchange="showFileName(this)">
                    <div class="file-name-text">ยังไม่ได้เลือกไฟล์</div>
                </div>
                <div class="excel-upload-group">
                    <button type="submit" class="excel-upload-button">อัปโหลดไฟล์</button>
                </div>
            </form>

            <!-- ประเภทขนม -->
            <form action="../../product/upload_product/upload_product_excal/upload_excel_snack.php" method="POST" enctype="multipart/form-data" class="excel-upload-form">
                <h4 class="excel-type-title"><span class="material-icons">cookie</span>ประเภทขนม
                </h4>
                <div class="excel-upload-group">
                    <input type="file" name="excel_file" class="excel-upload-input" accept=".xlsx, .xls" required onchange="showFileName(this)">
                    <div class="file-name-text">ยังไม่ได้เลือกไฟล์</div>
                </div>
                <div class="excel-upload-group">
                    <button type="submit" class="excel-upload-button">อัปโหลดไฟล์</button>
                </div>
            </form>
            <!-- ประเภทเครื่องเขียน -->
            <form action="../../product/upload_product/upload_product_excal/upload_excel_snack.php" method="POST" enctype="multipart/form-data" class="excel-upload-form">
                <h4 class="excel-type-title"><span class="material-icons">cookie</span>ประเภทเครื่องเขียน
                </h4>
                <div class="excel-upload-group">
                    <input type="file" name="excel_file" class="excel-upload-input" accept=".xlsx, .xls" required onchange="showFileName(this)">
                    <div class="file-name-text">ยังไม่ได้เลือกไฟล์</div>
                </div>
                <div class="excel-upload-group">
                    <button type="submit" class="excel-upload-button">อัปโหลดไฟล์</button>
                </div>
            </form>
        </div>

        <div class="download-grid">
            <div class="download-template">
                <p>ฟอร์ม Excal ของแห้ง</p>
                <a href="../../assets/templates/ฟอร์มของแห้ง.xlsx" download class="btn btn-download" data-tooltip="ฟอร์มของแห้ง">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="m720-120 160-160-56-56-64 64v-167h-80v167l-64-64-56 56 160 160ZM560 0v-80h320V0H560ZM240-160q-33 0-56.5-23.5T160-240v-560q0-33 23.5-56.5T240-880h280l240 240v121h-80v-81H480v-200H240v560h240v80H240Zm0-80v-560 560Z" />
                    </svg>
                </a>
            </div>

            <div class="download-template">
                <p>ฟอร์ม Excal ของเครื่องดื่ม</p>
                <a href="../../assets/templates/ฟอร์มเครื่องดื่ม.xlsx" download class="btn btn-download" data-tooltip="ฟอร์มเครื่องดื่ม">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="m720-120 160-160-56-56-64 64v-167h-80v167l-64-64-56 56 160 160ZM560 0v-80h320V0H560ZM240-160q-33 0-56.5-23.5T160-240v-560q0-33 23.5-56.5T240-880h280l240 240v121h-80v-81H480v-200H240v560h240v80H240Zm0-80v-560 560Z" />
                    </svg>
                </a>
            </div>
            <div class="download-template">
                <p>ฟอร์ม Excal ของแช่แข็ง</p>
                <a href="../../assets/templates/ฟอร์มของแช่แข็ง.xlsx" download class="btn btn-download" data-tooltip="ฟอร์มของแช่แข็ง">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="m720-120 160-160-56-56-64 64v-167h-80v167l-64-64-56 56 160 160ZM560 0v-80h320V0H560ZM240-160q-33 0-56.5-23.5T160-240v-560q0-33 23.5-56.5T240-880h280l240 240v121h-80v-81H480v-200H240v560h240v80H240Zm0-80v-560 560Z" />
                    </svg>
                </a>
            </div>
            <div class="download-template">
                <p>ฟอร์ม Excal ของขนม</p>
                <a href="../../assets/templates/ฟอร์มขนม.xlsx" download class="btn btn-download" data-tooltip="ฟอร์มขนม">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="m720-120 160-160-56-56-64 64v-167h-80v167l-64-64-56 56 160 160ZM560 0v-80h320V0H560ZM240-160q-33 0-56.5-23.5T160-240v-560q0-33 23.5-56.5T240-880h280l240 240v121h-80v-81H480v-200H240v560h240v80H240Zm0-80v-560 560Z" />
                    </svg>
                </a>
            </div>
            <div class="download-template">
                <p>ฟอร์ม Excal ของเครื่องเขียน</p>
                <a href="../../assets/templates/ฟอร์มขนม.xlsx" download class="btn btn-download" data-tooltip="ฟอร์มเครื่องเขียน">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="m720-120 160-160-56-56-64 64v-167h-80v167l-64-64-56 56 160 160ZM560 0v-80h320V0H560ZM240-160q-33 0-56.5-23.5T160-240v-560q0-33 23.5-56.5T240-880h280l240 240v121h-80v-81H480v-200H240v560h240v80H240Zm0-80v-560 560Z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- ส่วนของการตรวจสอบเพิ่มสินค้า -->
    <div id="food_bank_check" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">
                รายละเอียดสินค้าประเภทแห้ง
            </h3>

            <div class="btn-container">
                <button id="add_dried_food" class="btn btn-dried-food" data-tooltip="เพิ่มสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>
                </button>

                <button class="btn btn-edit-product-localStorage" data-tooltip="แก้ไขสินค้า" data-key="dried_food">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                    </svg>
                </button>

                <button class="btn btn-delete-product" data-tooltip="ลบสินค้า" data-key="dried_food">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                    </svg>
                </button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="center-checkbox"><input type="checkbox" onclick="toggleSelectAllForTable(this, 'dried_food_table')"></th>
                    <th>ชื่อสินค้า</th>
                    <th>รูปภาพ</th>
                    <th>บาร์โค้ด</th>
                    <th>ราคาขาย</th>
                    <th>ราคาต้นทุน</th>
                    <th>จำนวนสต็อก</th>
                    <th>สต็อกต่ำสุด</th>
                </tr>
            </thead>
            <tbody id="dried_food_table">
            </tbody>
        </table>
    </div>

    <div id="local_drink_check" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">รายละเอียดสินค้าประเภทเครื่องดื่ม
            </h3>

            <div class="btn-container">
                <button id="add_soft_drink" class="btn btn-soft-drink" data-tooltip="เพิ่มสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>
                </button>

                <button class="btn btn-edit-product-localStorage" data-tooltip="แก้ไขสินค้า" data-key="soft_drink">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                    </svg>
                </button>

                <button class="btn btn-delete-product" data-tooltip="ลบสินค้า" data-key="soft_drink">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                    </svg>
                </button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="center-checkbox"><input type="checkbox" onclick="toggleSelectAllForTable(this, 'soft_drink_table')"></th>
                    <th>ชื่อสินค้า</th>
                    <th>รูปภาพ</th>
                    <th>บาร์โค้ด</th>
                    <th>ราคาขาย</th>
                    <th>ราคาต้นทุน</th>
                    <th>จำนวนสต็อก</th>
                    <th>สต็อกต่ำสุด</th>
                </tr>
            </thead>
            <tbody id="soft_drink_table"></tbody>
        </table>
    </div>

    <div id="fastfood_check" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">รายละเอียดสินค้าประเภทแช่แข็ง
            </h3>

            <div class="btn-container">
                <button id="add_fresh_food" class="btn btn-fresh-food" data-tooltip="เพิ่มสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>
                </button>

                <button class="btn btn-edit-product-localStorage" data-tooltip="แก้ไขสินค้า" data-key="fresh_food">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                    </svg>
                </button>

                <button class="btn btn-delete-product" data-tooltip="ลบสินค้า" data-key="fresh_food">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                    </svg>
                </button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="center-checkbox"><input type="checkbox" onclick="toggleSelectAllForTable(this, 'fresh_food_table')"></th>
                    <th>ชื่อสินค้า</th>
                    <th>รูปภาพ</th>
                    <th>บาร์โค้ด</th>
                    <th>ราคาขาย</th>
                    <th>ราคาต้นทุน</th>
                    <th>จำนวนสต็อก</th>
                    <th>สต็อกต่ำสุด</th>
                </tr>
            </thead>
            <tbody id="fresh_food_table"></tbody>
        </table>
    </div>

    <div id="snack_check" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">รายละเอียดสินค้าประเภทขนมขบเคี้ยว
            </h3>

            <div class="btn-container">
                <button id="add_snack" class="btn btn-snack" data-tooltip="เพิ่มสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>
                </button>

                <button class="btn btn-edit-product-localStorage" data-tooltip="แก้ไขสินค้า" data-key="snack">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                    </svg>
                </button>

                <button class="btn btn-delete-product" data-tooltip="ลบสินค้า" data-key="snack">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                    </svg>
                </button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="center-checkbox"><input type="checkbox" onclick="toggleSelectAllForTable(this, 'snack_table')"></th>
                    <th>ชื่อสินค้า</th>
                    <th>รูปภาพ</th>
                    <th>บาร์โค้ด</th>
                    <th>ราคาขาย</th>
                    <th>ราคาต้นทุน</th>
                    <th>จำนวนสต็อก</th>
                    <th>สต็อกต่ำสุด</th>
                </tr>
            </thead>
            <tbody id="snack_table"></tbody>
        </table>
    </div>

    <div id="stationery_check" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">รายละเอียดสินค้าประเภทเครื่องเขียน
            </h3>

            <div class="btn-container">
                <button id="add_stationery" class="btn btn-stationery" data-tooltip="เพิ่มสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>
                </button>

                <button class="btn btn-edit-product-localStorage" data-tooltip="แก้ไขสินค้า" data-key="stationery">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                    </svg>
                </button>

                <button class="btn btn-delete-product" data-tooltip="ลบสินค้า" data-key="stationery">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                    </svg>
                </button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="center-checkbox"><input type="checkbox" onclick="toggleSelectAllForTable(this, 'stationery_table')"></th>
                    <th>ชื่อสินค้า</th>
                    <th>รูปภาพ</th>
                    <th>บาร์โค้ด</th>
                    <th>ราคาขาย</th>
                    <th>ราคาต้นทุน</th>
                    <th>จำนวนสต็อก</th>
                    <th>สต็อกต่ำสุด</th>
                </tr>
            </thead>
            <tbody id="stationery_table"></tbody>
        </table>
    </div>

    <!-- Modal Edit Form loaclStorage-->
    <div id="editModal">
        <div class="modal-content">
            <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path d="M560-80v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T903-300L683-80H560Zm300-263-37-37 37 37ZM620-140h38l121-122-18-19-19-18-122 121v38ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v120h-80v-80H520v-200H240v640h240v80H240Zm280-400Zm241 199-19-18 37 37-18-19Z" />
                </svg>แบบฟอร์มแก้ไขสินค้า</h3>
            <form id="editForm" class="modal-form">
                <div class="modal-flex">
                    <!-- รูปภาพ -->
                    <div class="modal-image">
                        <img id="editImagePreview" src="" alt="รูปสินค้า" />
                    </div>

                    <!-- ฟอร์ม -->
                    <div class="modal-fields">
                        <label for="editName">ชื่อสินค้า
                        </label>
                        <input type="text" id="editName" required />

                        <label>บาร์โค้ด</label>
                        <input type="text" id="editBarcode" required />

                        <div class="field-row">
                            <div>
                                <label for="editPrice">ราคาขาย</label>
                                <input type="number" id="editPrice" required />
                            </div>
                            <div>
                                <label for="editCost">ราคาต้นทุน</label>
                                <input type="number" id="editCost" required />
                            </div>
                        </div>

                        <div class="field-row">
                            <div>
                                <label for="editStock">จำนวนสต็อก</label>
                                <input type="number" id="editStock" required />
                            </div>
                            <div>
                                <label for="editReorder">สต็อกต่ำสุด</label>
                                <input type="number" id="editReorder" required />
                            </div>
                        </div>
                        <p class="image-warning"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                <path d="m40-120 440-760 440 760H40Zm440-120q17 0 28.5-11.5T520-280q0-17-11.5-28.5T480-320q-17 0-28.5 11.5T440-280q0 17 11.5 28.5T480-240Zm-40-120h80v-200h-80v200Z" />
                            </svg>กรุณาตรวจสอบข้อมูลอีกครั้งเพื่อความถูกต้อง</p>

                        <div class="modal-buttons field-row">
                            <button type="submit" class="modal-btn modal-btn-save"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d=" M200-200v-560 454-85 191Zm0 80q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v320h-80v-320H200v560h280v80H200Zm494 40L552-222l57-56 85 85 170-170 56 57L694-80ZM320-440q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 160h240v-80H440v80Zm0-160h240v-80H440v80Z" />
                                </svg>บันทึกการแก้ไข</button>
                            <button type="button" id="closeModal" class="modal-btn modal-btn-cancel">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="confirmDeleteProductModal" class="custom-modal hidden">
        <div class="custom-modal-content">
            <span class="material-icons">feedback</span>
            <p id="confirmDeleteMessage"></p>
            <div class="modal-actions">
                <button id="btnConfirmDeleteProduct" class="btn-Confirm btn-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                    </svg>ลบสินค้า
                </button>
                <button id="btnCancelDeleteProduct" class="btn-Confirm btn-secondary">
                    ยกเลิก
                </button>
            </div>
        </div>
    </div>


    <div id="deleteConfirmModal" class="custom-modal hidden">
        <div class="custom-modal-content">
            <span class="material-icons">feedback</span>
            <p id="deleteMessage"></p>
            <div class="modal-actions">
                <button id="confirmDeleteBtn" class="btn-Confirm btn-danger"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                    </svg>ลบสินค้า</button>
                <button id="cancelDeleteBtn" class="btn-Confirm btn-secondary">ยกเลิก</button>
            </div>
        </div>
    </div>

    <div id="addProductConfirmModal" class="custom-modal hidden">
        <div class="custom-modal-content">
            <span class="material-icons">trolley</span>
            <p id="confirmMessage">คุณต้องการเพิ่มสินค้าที่เลือกหรือไม่?</p>
            <div class="modal-actions">
                <button id="confirmAddProductBtn" class="btn-Confirm btn-success"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M467-120q-73-1-136-14t-110-34.5q-47-21.5-74-50T120-280q0 33 27 61.5t74 50Q268-147 331-134t136 14Zm-15-200q-38-2-73.5-6.5t-67.5-12q-32-7.5-60-17.5t-51-23q23 13 51 23t60 17.5q32 7.5 67.5 12T452-320Zm28-279q89 0 179-26.5T760-679q-11-29-100.5-55T480-760q-91 0-178.5 25.5T200-679q14 27 101.5 53.5T480-599Zm220 479h40v-164l72 72 28-28-120-120-120 120 28 28 72-72v164Zm20 80q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM443-201q3 22 9 42t15 39q-73-1-136-14t-110-34.5q-47-21.5-74-50T120-280v-400q0-66 105.5-113T480-840q149 0 254.5 47T840-680v187q-19-9-39-15t-41-9v-62q-52 29-124 44t-156 15q-85 0-157-15t-123-44v101q51 47 130.5 62.5T480-400h11q-13 18-22.5 38T452-320q-76-4-141-18.5T200-379v99q7 13 30 26.5t56 24q33 10.5 73.5 18T443-201Z" />
                    </svg>เพิ่มสินค้า</button>
                <button id="cancelAddProductBtn" class="btn-Confirm btn-secondary">ยกเลิก</button>
            </div>
        </div>
    </div>

    <div id="logoutConfirmModal" class="custom-modal hidden">
        <div class="custom-modal-content">
            <span class="material-icons">warning</span>
            <p id="confirmMessage">คุณต้องการออกจากระบบหรือไม่?</p>
            <div class="modal-actions">
                <button id="confirmLogoutBtn" class="btn-Confirm btn-danger"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z" />
                    </svg>ยืนยันออกจากระบบ</button>
                <button id="cancelLogoutBtn" class="btn-Confirm btn-secondary">ยกเลิก
                </button>
            </div>
        </div>
    </div>


    <div id="deleteSuccessToast" class="custom-toast">
        <span class="material-icons">check_circle</span>
        <p id="deleteSuccessMessage"></p>
    </div>

    <div id="alertToast" class="alert-toast">
        <span id="alertToastIcon" class="icon-container"></span>
        <span id="alertToastMessage"></span>
    </div>


    <div id="dried_food" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">
                <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#ffffff">
                    <path d="M400-240h40v-160q25 0 42.5-17.5T500-460v-120h-40v120h-20v-120h-40v120h-20v-120h-40v120q0 25 17.5 42.5T400-400v160Zm160 0h40v-340q-33 0-56.5 23.5T520-500v120h40v140ZM160-120v-480l320-240 320 240v480H160Z" />
                </svg>
                สินค้าประเภทแห้ง
            </h3>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="ค้นหาสินค้า..." id="searchInput" oninput="filterProducts()">
                <button class="search-button"
                    onclick="filterProducts()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                    </svg>
                </button>
            </div>

            <div class="btn-container">
                <button class="btn btn-filter" data-tooltip="แสดง Filter" onclick="toggleMenu()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M400-240v-80h160v80H400ZM240-440v-80h480v80H240ZM120-640v-80h720v80H120Z" />
                    </svg>
                </button>

                <div id="filterMenu" class="filter-menu">
                    <!-- ประเภทสินค้า -->
                    <select id="filterCategory" onchange="applyFilters()">
                        <option value="all">ทั้งหมด</option>
                        <option value="lowStock">สินค้าใกล้หมด</option>
                    </select>

                    <!-- จำนวนแสดงผลต่อหน้า -->
                    <select id="itemsPerPageSelect" onchange="changeItemsPerPage(this.value)">
                        <option value="25">แสดง 25 ชิ้น</option>
                        <option value="50">แสดง 50 ชิ้น</option>
                        <option value="100">แสดง 100 ชิ้น</option>
                    </select>
                </div>


                <button class="btn btn-table" data-tooltip="แสดง Table" onclick="switchView('table')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M320-80q-33 0-56.5-23.5T240-160v-480q0-33 23.5-56.5T320-720h480q33 0 56.5 23.5T880-640v480q0 33-23.5 56.5T800-80H320Zm0-80h200v-120H320v120Zm280 0h200v-120H600v120ZM80-240v-560q0-33 23.5-56.5T160-880h560v80H160v560H80Zm240-120h200v-120H320v120Zm280 0h200v-120H600v120ZM320-560h480v-80H320v80Z" />
                    </svg>
                </button>

                <button class="btn btn-grid" data-tooltip="แสดง Grid" onclick="switchView('grid')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520ZM200-600h160v-160H200v160Zm400 0h160v-160H600v160Zm0 400h160v-160H600v160Zm-400 0h160v-160H200v160Zm400-400Zm0 240Zm-240 0Zm0-240Z" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="container">
            <table id="productTable" style="display: none;">
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>รูปภาพ</th>
                        <th>บาร์โค้ด</th>
                        <th>ราคาขาย</th>
                        <th>ราคาต้นทุน</th>
                        <th>จำนวนสต็อก</th>
                        <th>สต็อกต่ำสุด</th>
                        <th>จัดการสินค้า</th>
                    </tr>
                </thead>
                <tbody id="productTableBody"></tbody>
            </table>

            <div id="productGrid" class="card-grid"></div>
        </div>

        <button id="scrollTopBtn" onclick="scrollToTop()" data-tooltip="เลื่อนขึ้น"><svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="#e3e3e3">
                <path d="M440-320h80v-168l64 64 56-56-160-160-160 160 56 56 64-64v168Zm40 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
            </svg></button>

        <div id="pagination" class="pagination"></div>

        <div id="overlay" class="overlay" onclick="closeEditPanel()"></div>

        <div id="editPanel" class="side-panel">
            <div class="side-panel-content">
                <span class="side-panel-close-btn" onclick="closeEditPanel()">&times;</span>

                <div id="side-panel-form-content">
                    <form id="editProductForm">
                        <input type="hidden" id="edit_product_id" name="id">

                        <div id="imagePreviewContainer">
                            <img id="imagePreview" alt="รูปสินค้า">
                        </div>

                        <label for="product_name"><span class="material-icons">sell</span>ชื่อสินค้า</label>
                        <input type="text" id="product_name" name="product_name" required>

                        <label for="barcode">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333">
                                <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                            </svg>บาร์โค้ด

                        </label>
                        <input type="text" id="barcodeDriedFood" name="barcode" maxlength="17" />

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="price"><span class="material-icons">price_change</span>ราคาขาย</label>
                                <input type="number" id="price" name="price" required>
                            </div>
                            <div class="group-item">
                                <label for="cost"><span class="material-icons">money</span>ราคาต้นทุน</label>
                                <input type="number" id="cost" name="cost" required>
                            </div>
                        </div>

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="stock"><span class="material-icons">warehouse</span>จำนวนสต็อก</label>
                                <input type="number" id="stock" name="stock" required>
                            </div>
                            <div class="group-item">
                                <label for="reorder_level"><span class="material-icons">swap_vert</span>สต็อกต่ำสุด</label>
                                <input type="number" id="reorder_level" name="reorder_level" required>
                            </div>
                        </div>

                        <button type="submit">

                            บันทึกการแก้ไข
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- หมวด soft drink -->
    <div id="soft_drink" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">
                <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#ffffff">
                    <path d="M400-240h40v-160q25 0 42.5-17.5T500-460v-120h-40v120h-20v-120h-40v120h-20v-120h-40v120q0 25 17.5 42.5T400-400v160Zm160 0h40v-340q-33 0-56.5 23.5T520-500v120h40v140ZM160-120v-480l320-240 320 240v480H160Z" />
                </svg>
                สินค้าเครื่องดื่ม
            </h3>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="ค้นหาสินค้า..." id="searchInputSoftDrink"
                    oninput="filterProductsSoftDrink()">
                <button class="search-button" onclick="filterProductsSoftDrink()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                    </svg>
                </button>
            </div>

            <div class="btn-container">
                <button class="btn btn-filter" data-tooltip="แสดง Filter" onclick="toggleMenuSoftDrink()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M400-240v-80h160v80H400ZM240-440v-80h480v80H240ZM120-640v-80h720v80H120Z" />
                    </svg>
                </button>

                <div id="filterMenuSoftDrink" class="filter-menu">
                    <select id="filterCategorySoftDrink" onchange="applyFiltersSoftDrink()">
                        <option value="all">ทั้งหมด</option>
                        <option value="lowStock">สินค้าใกล้หมด</option>
                    </select>

                    <select id="itemsPerPageSelectSoftDrink" onchange="changeItemsPerPageSoftDrink(this.value)">
                        <option value="25">แสดง 25 ชิ้น</option>
                        <option value="50">แสดง 50 ชิ้น</option>
                        <option value="100">แสดง 100 ชิ้น</option>
                    </select>
                </div>

                <button class="btn btn-table" data-tooltip="แสดง Table" onclick="switchViewSoftDrink('table')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M320-80q-33 0-56.5-23.5T240-160v-480q0-33 23.5-56.5T320-720h480q33 0 56.5 23.5T880-640v480q0 33-23.5 56.5T800-80H320Zm0-80h200v-120H320v120Zm280 0h200v-120H600v120ZM80-240v-560q0-33 23.5-56.5T160-880h560v80H160v560H80Zm240-120h200v-120H320v120Zm280 0h200v-120H600v120ZM320-560h480v-80H320v80Z" />
                    </svg>
                </button>
                <button class="btn btn-grid" data-tooltip="แสดง Grid" onclick="switchViewSoftDrink('grid')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520ZM200-600h160v-160H200v160Zm400 0h160v-160H600v160Zm0 400h160v-160H600v160Zm-400 0h160v-160H200v160Zm400-400Zm0 240Zm-240 0Zm0-240Z" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="containerSoftDrink">
            <table id="productTableSoftDrink" style="display: none;">
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>รูปภาพ</th>
                        <th>บาร์โค้ด</th>
                        <th>ราคาขาย</th>
                        <th>ราคาต้นทุน</th>
                        <th>จำนวนสต็อก</th>
                        <th>สต็อกต่ำสุด</th>
                        <th>จัดการสินค้า</th>
                    </tr>
                </thead>
                <tbody id="productTableBodySoftDrink"></tbody>
            </table>

            <div id="productGridSoftDrink" class="card-grid"></div>
        </div>

        <button id="scrollTopBtnSoftDrink" onclick="scrollToTopSoftDrink()" data-tooltip="เลื่อนขึ้น"><svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="#e3e3e3">
                <path d="M440-320h80v-168l64 64 56-56-160-160-160 160 56 56 64-64v168Zm40 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
            </svg></button>

        <div id="paginationSoftDrink" class="pagination"></div>

        <div id="overlaySoftDrink" class="overlay" onclick="closeEditPanelSoftDrink()"></div>

        <div id="editPanelSoftDrink" class="side-panel">
            <div class="side-panel-content">
                <span class="side-panel-close-btn" onclick="closeEditPanelSoftDrink()">&times;</span>

                <div id="side-panel-form-content-softdrink">
                    <form id="editProductFormSoftDrink">
                        <input type="hidden" id="edit_product_id_softdrink" name="id">

                        <div id="imagePreviewContainerSoftDrink">
                            <img id="imagePreviewSoftDrink" alt="รูปสินค้า">
                        </div>

                        <label for="product_name_softdrink"><span class="material-icons">sell</span>ชื่อสินค้า</label>
                        <input type="text" id="product_name_softdrink" name="product_name" required>

                        <label for="barcode_softdrink">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333">
                                <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                            </svg>บาร์โค้ด</label>
                        <input type="text" id="barcode_softdrink" name="barcode" maxlength="17" />

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="price_softdrink"><span class="material-icons">price_change</span>ราคาขาย</label>
                                <input type="number" id="price_softdrink" name="price" required>
                            </div>
                            <div class="group-item">
                                <label for="cost_softdrink"><span class="material-icons">money</span>ราคาต้นทุน</label>
                                <input type="number" id="cost_softdrink" name="cost" required>
                            </div>
                        </div>

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="stock_softdrink"><span class="material-icons">warehouse</span>จำนวนสต็อก</label>
                                <input type="number" id="stock_softdrink" name="stock" required>
                            </div>
                            <div class="group-item">
                                <label for="reorder_level_softdrink"><span class="material-icons">swap_vert</span>สต็อกต่ำสุด</label>
                                <input type="number" id="reorder_level_softdrink" name="reorder_level" required>
                            </div>
                        </div>

                        <button type="submit">บันทึกการแก้ไข</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- หมวด fresh food -->
    <div id="fresh_food" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">
                <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#ffffff">
                    <path d="M400-240h40v-160q25 0 42.5-17.5T500-460v-120h-40v120h-20v-120h-40v120h-20v-120h-40v120q0 25 17.5 42.5T400-400v160Zm160 0h40v-340q-33 0-56.5 23.5T520-500v120h40v140ZM160-120v-480l320-240 320 240v480H160Z" />
                </svg>
                สินค้าแช่แข็ง
            </h3>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="ค้นหาสินค้า..." id="searchInputFreshFood" oninput="filterProductsFreshFood()">
                <button class="search-button" onclick="filterProductsFreshFood()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                    </svg>
                </button>
            </div>

            <div class="btn-container">
                <button class="btn btn-filter" data-tooltip="แสดง Filter" onclick="toggleMenuFreshFood()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M400-240v-80h160v80H400ZM240-440v-80h480v80H240ZM120-640v-80h720v80H120Z" />
                    </svg>
                </button>

                <div id="filterMenuFreshFood" class="filter-menu">
                    <select id="filterCategoryFreshFood" onchange="applyFiltersFreshFood()">
                        <option value="all">ทั้งหมด</option>
                        <option value="lowStock">สินค้าใกล้หมด</option>
                    </select>

                    <select id="itemsPerPageSelectFreshFood" onchange="changeItemsPerPageFreshFood(this.value)">
                        <option value="25">แสดง 25 ชิ้น</option>
                        <option value="50">แสดง 50 ชิ้น</option>
                        <option value="100">แสดง 100 ชิ้น</option>
                    </select>
                </div>

                <button class="btn btn-table" data-tooltip="แสดง Table" onclick="switchViewFreshFood('table')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M320-80q-33 0-56.5-23.5T240-160v-480q0-33 23.5-56.5T320-720h480q33 0 56.5 23.5T880-640v480q0 33-23.5 56.5T800-80H320Zm0-80h200v-120H320v120Zm280 0h200v-120H600v120ZM80-240v-560q0-33 23.5-56.5T160-880h560v80H160v560H80Zm240-120h200v-120H320v120Zm280 0h200v-120H600v120ZM320-560h480v-80H320v80Z" />
                    </svg>
                </button>
                <button class="btn btn-grid" data-tooltip="แสดง Grid" onclick="switchViewFreshFood('grid')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520ZM200-600h160v-160H200v160Zm400 0h160v-160H600v160Zm0 400h160v-160H600v160Zm-400 0h160v-160H200v160Zm400-400Zm0 240Zm-240 0Zm0-240Z" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="containerFreshFood">
            <table id="productTableFreshFood" style="display: none;">
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>รูปภาพ</th>
                        <th>บาร์โค้ด</th>
                        <th>ราคาขาย</th>
                        <th>ราคาต้นทุน</th>
                        <th>จำนวนสต็อก</th>
                        <th>สต็อกต่ำสุด</th>
                        <th>จัดการสินค้า</th>
                    </tr>
                </thead>
                <tbody id="productTableBodyFreshFood"></tbody>
            </table>

            <div id="productGridFreshFood" class="card-grid"></div>
        </div>

        <button id="scrollTopBtnFreshFood" onclick="scrollToTopFreshFood()" data-tooltip="เลื่อนขึ้น"><svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="#e3e3e3">
                <path d="M440-320h80v-168l64 64 56-56-160-160-160 160 56 56 64-64v168Zm40 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
            </svg></button>

        <div id="paginationFreshFood" class="pagination"></div>

        <div id="overlayFreshFood" class="overlay" onclick="closeEditPanelFreshFood()"></div>

        <div id="editPanelFreshFood" class="side-panel">
            <div class="side-panel-content">
                <span class="side-panel-close-btn" onclick="closeEditPanelFreshFood()">&times;</span>

                <div id="side-panel-form-content-freshfood">
                    <form id="editProductFormFreshFood" method="POST" action="">
                        <input type="hidden" id="edit_product_id_freshfood" name="id">

                        <div id="imagePreviewContainerFreshFood">
                            <img id="imagePreviewFreshFood" alt="รูปสินค้า">
                        </div>

                        <label for="product_name_freshfood"><span class="material-icons">sell</span>ชื่อสินค้า</label>
                        <input type="text" id="product_name_freshfood" name="product_name" required>

                        <label for="barcode_freshfood"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333">
                                <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                            </svg>บาร์โค้ด</label>
                        <input type="text" id="barcode_freshfood" name="barcode" maxlength="17" />

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="price_freshfood"><span class="material-icons">price_change</span>ราคาขาย</label>
                                <input type="number" id="price_freshfood" name="price" required>
                            </div>
                            <div class="group-item">
                                <label for="cost_freshfood"><span class="material-icons">money</span>ราคาต้นทุน</label>
                                <input type="number" id="cost_freshfood" name="cost" required>
                            </div>
                        </div>

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="stock_freshfood"><span class="material-icons">warehouse</span>จำนวนสต็อก</label>
                                <input type="number" id="stock_freshfood" name="stock" required>
                            </div>
                            <div class="group-item">
                                <label for="reorder_level_freshfood"><span class="material-icons">swap_vert</span>สต็อกต่ำสุด</label>
                                <input type="number" id="reorder_level_freshfood" name="reorder_level" required>
                            </div>
                        </div>

                        <button type="submit">บันทึกการแก้ไข</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- หมวด snack food -->
    <div id="snack_food" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">
                <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#ffffff">
                    <path d="M400-240h40v-160q25 0 42.5-17.5T500-460v-120h-40v120h-20v-120h-40v120h-20v-120h-40v120q0 25 17.5 42.5T400-400v160Zm160 0h40v-340q-33 0-56.5 23.5T520-500v120h40v140ZM160-120v-480l320-240 320 240v480H160Z" />
                </svg>
                สินค้าขนมขบเคี้ยว
            </h3>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="ค้นหาสินค้า..." id="searchInputSnackFood" oninput="filterProductsSnackFood()">
                <button class="search-button" onclick="filterProductsSnackFood()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                    </svg>
                </button>
            </div>

            <div class="btn-container">
                <button class="btn btn-filter" data-tooltip="แสดง Filter" onclick="toggleMenuSnackFood()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M400-240v-80h160v80H400ZM240-440v-80h480v80H240ZM120-640v-80h720v80H120Z" />
                    </svg>
                </button>

                <div id="filterMenuSnackFood" class="filter-menu">
                    <select id="filterCategorySnackFood" onchange="applyFiltersSnackFood()">
                        <option value="all">ทั้งหมด</option>
                        <option value="lowStock">สินค้าใกล้หมด</option>
                    </select>

                    <select id="itemsPerPageSelectSnackFood" onchange="changeItemsPerPageSnackFood(this.value)">
                        <option value="25">แสดง 25 ชิ้น</option>
                        <option value="50">แสดง 50 ชิ้น</option>
                        <option value="100">แสดง 100 ชิ้น</option>
                    </select>
                </div>

                <button class="btn btn-table" data-tooltip="แสดง Table" onclick="switchViewSnackFood('table')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M320-80q-33 0-56.5-23.5T240-160v-480q0-33 23.5-56.5T320-720h480q33 0 56.5 23.5T880-640v480q0 33-23.5 56.5T800-80H320Zm0-80h200v-120H320v120Zm280 0h200v-120H600v120ZM80-240v-560q0-33 23.5-56.5T160-880h560v80H160v560H80Zm240-120h200v-120H320v120Zm280 0h200v-120H600v120ZM320-560h480v-80H320v80Z" />
                    </svg>
                </button>
                <button class="btn btn-grid" data-tooltip="แสดง Grid" onclick="switchViewSnackFood('grid')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520ZM200-600h160v-160H200v160Zm400 0h160v-160H600v160Zm0 400h160v-160H600v160Zm-400 0h160v-160H200v160Zm400-400Zm0 240Zm-240 0Zm0-240Z" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="containerSnackFood">
            <table id="productTableSnackFood" style="display: none;">
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>รูปภาพ</th>
                        <th>บาร์โค้ด</th>
                        <th>ราคาขาย</th>
                        <th>ราคาต้นทุน</th>
                        <th>จำนวนสต็อก</th>
                        <th>สต็อกต่ำสุด</th>
                        <th>จัดการสินค้า</th>
                    </tr>
                </thead>
                <tbody id="productTableBodySnackFood"></tbody>
            </table>

            <div id="productGridSnackFood" class="card-grid"></div>
        </div>

        <button id="scrollTopBtnSnackFood" onclick="scrollToTopSnackFood()" data-tooltip="เลื่อนขึ้น"><svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="#e3e3e3">
                <path d="M440-320h80v-168l64 64 56-56-160-160-160 160 56 56 64-64v168Zm40 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
            </svg></button>

        <div id="paginationSnackFood" class="pagination"></div>

        <div id="overlaySnackFood" class="overlay" onclick="closeEditPanelSnackFood()"></div>

        <div id="editPanelSnackFood" class="side-panel">
            <div class="side-panel-content">
                <span class="side-panel-close-btn" onclick="closeEditPanelSnackFood()">&times;</span>

                <div id="side-panel-form-content-snackfood">
                    <!-- ฟอร์มที่ใช้แก้ไขสินค้า -->
                    <form id="editProductFormSnackFood" method="POST" action="">
                        <input type="hidden" id="edit_product_id_snackfood" name="id">

                        <div id="imagePreviewContainerSnackFood">
                            <img id="imagePreviewSnackFood" alt="รูปสินค้า">
                        </div>

                        <label for="product_name_snackfood"><span class="material-icons">sell</span>ชื่อสินค้า</label>
                        <input type="text" id="product_name_snackfood" name="product_name" required>

                        <label for="barcode_snackfood"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333">
                                <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                            </svg>บาร์โค้ด</label>
                        <input type="text" id="barcode_snackfood" name="barcode" maxlength="17" />

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="price_snackfood"><span class="material-icons">price_change</span>ราคาขาย</label>
                                <input type="number" id="price_snackfood" name="price" required>
                            </div>
                            <div class="group-item">
                                <label for="cost_snackfood"><span class="material-icons">money</span>ราคาต้นทุน</label>
                                <input type="number" id="cost_snackfood" name="cost" required>
                            </div>
                        </div>

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="stock_snackfood"><span class="material-icons">warehouse</span>จำนวนสต็อก</label>
                                <input type="number" id="stock_snackfood" name="stock" required>
                            </div>
                            <div class="group-item">
                                <label for="reorder_level_snackfood"><span class="material-icons">swap_vert</span>สต็อกต่ำสุด</label>
                                <input type="number" id="reorder_level_snackfood" name="reorder_level" required>
                            </div>
                        </div>

                        <button type="submit">บันทึกการแก้ไข</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div id="stationery" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">
                <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#ffffff">
                    <path d="M400-240h40v-160q25 0 42.5-17.5T500-460v-120h-40v120h-20v-120h-40v120h-20v-120h-40v120q0 25 17.5 42.5T400-400v160Zm160 0h40v-340q-33 0-56.5 23.5T520-500v120h40v140ZM160-120v-480l320-240 320 240v480H160Z" />
                </svg>
                สินค้าเครื่องเขียน
            </h3>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="ค้นหาสินค้า..." id="searchInputStationery" oninput="filterProductsStationery()">
                <button class="search-button" onclick="filterProductsStationery()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                    </svg>
                </button>
            </div>

            <div class="btn-container">
                <button class="btn btn-filter" data-tooltip="แสดง Filter" onclick="toggleMenuStationery()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M400-240v-80h160v80H400ZM240-440v-80h480v80H240ZM120-640v-80h720v80H120Z" />
                    </svg>
                </button>

                <div id="filterMenuStationery" class="filter-menu">
                    <select id="filterCategoryStationery" onchange="applyFiltersStationery()">
                        <option value="all">ทั้งหมด</option>
                        <option value="lowStock">สินค้าใกล้หมด</option>
                    </select>

                    <select id="itemsPerPageSelectStationery" onchange="changeItemsPerPageStationery(this.value)">
                        <option value="25">แสดง 25 ชิ้น</option>
                        <option value="50">แสดง 50 ชิ้น</option>
                        <option value="100">แสดง 100 ชิ้น</option>
                    </select>
                </div>

                <button class="btn btn-table" data-tooltip="แสดง Table" onclick="switchViewStationery('table')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M320-80q-33 0-56.5-23.5T240-160v-480q0-33 23.5-56.5T320-720h480q33 0 56.5 23.5T880-640v480q0 33-23.5 56.5T800-80H320Zm0-80h200v-120H320v120Zm280 0h200v-120H600v120ZM80-240v-560q0-33 23.5-56.5T160-880h560v80H160v560H80Zm240-120h200v-120H320v120Zm280 0h200v-120H600v120ZM320-560h480v-80H320v80Z" />
                    </svg>
                </button>
                <button class="btn btn-grid" data-tooltip="แสดง Grid" onclick="switchViewStationery('grid')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520ZM200-600h160v-160H200v160Zm400 0h160v-160H600v160Zm0 400h160v-160H600v160Zm-400 0h160v-160H200v160Zm400-400Zm0 240Zm-240 0Zm0-240Z" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="containerStationery">
            <table id="productTableStationery" style="display: none;">
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>รูปภาพ</th>
                        <th>บาร์โค้ด</th>
                        <th>ราคาขาย</th>
                        <th>ราคาต้นทุน</th>
                        <th>จำนวนสต็อก</th>
                        <th>สต็อกต่ำสุด</th>
                        <th>จัดการสินค้า</th>
                    </tr>
                </thead>
                <tbody id="productTableBodyStationery"></tbody>
            </table>

            <div id="productGridStationery" class="card-grid"></div>
        </div>

        <button id="scrollTopBtnStationery" onclick="scrollToTopStationery()" data-tooltip="เลื่อนขึ้น"><svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="#e3e3e3">
                <path d="M440-320h80v-168l64 64 56-56-160-160-160 160 56 56 64-64v168Zm40 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
            </svg></button>

        <div id="paginationStationery" class="pagination"></div>

        <div id="overlayStationery" class="overlay" onclick="closeEditPanelStationery()"></div>

        <div id="editPanelStationery" class="side-panel">
            <div class="side-panel-content">
                <span class="side-panel-close-btn" onclick="closeEditPanelStationery()">&times;</span>

                <div id="side-panel-form-content-Stationery">
                    <!-- ฟอร์มที่ใช้แก้ไขสินค้า -->
                    <form id="editProductFormStationery" method="POST" action="">
                        <input type="hidden" id="edit_product_id_stationery" name="id">

                        <div id="imagePreviewContainerStationery">
                            <img id="imagePreviewStationery" alt="รูปสินค้า">
                        </div>

                        <label for="product_name_stationery"><span class="material-icons">sell</span>ชื่อสินค้า</label>
                        <input type="text" id="product_name_stationery" name="product_name" required>

                        <label for="barcode_stationery"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333">
                                <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                            </svg>บาร์โค้ด</label>
                        <input type="text" id="barcode_stationery" name="barcode" maxlength="17" />

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="price_stationery"><span class="material-icons">price_change</span>ราคาขาย</label>
                                <input type="number" id="price_stationery" name="price" required>
                            </div>
                            <div class="group-item">
                                <label for="cost_stationery"><span class="material-icons">money</span>ราคาต้นทุน</label>
                                <input type="number" id="cost_stationery" name="cost" required>
                            </div>
                        </div>

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="stock_stationery"><span class="material-icons">warehouse</span>จำนวนสต็อก</label>
                                <input type="number" id="stock_stationery" name="stock" required>
                            </div>
                            <div class="group-item">
                                <label for="reorder_level_stationery"><span class="material-icons">swap_vert</span>สต็อกต่ำสุด</label>
                                <input type="number" id="reorder_level_stationery" name="reorder_level" required>
                            </div>
                        </div>

                        <button type="submit">บันทึกการแก้ไข</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="sci_admin" class="content">
        <h2 class="h-text-upload">การจัดการแอดมิน
            <p>Admin Management</p>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="ค้นหา Gmail ผู้ดูแลระบบ..." id="searchAdminInput" oninput="filterAdminsByGmail()">
                <button class="search-button" onclick="filterAdminsByGmail()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                    </svg>
                </button>
            </div>
        </h2>
        <!-- ตารางข้อมูลผู้ใช้ -->
        <table>
            <thead>
                <tr>
                    <th>รูปโปรไฟล์</th>
                    <th>อีเมล</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <th>จัดการผู้ดูแล</th>
                </tr>
            </thead>
            <tbody id="adminTableBody">
                <?php if (!empty($admins)): ?>
                    <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td>
                                <?php if ($admin['profile_image']): ?>
                                    <img src="<?= $admin['profile_image'] ?>" width="80" height="auto" alt="Profile">
                                <?php else: ?>
                                    ไม่มีภาพ
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($admin['gmail']) ?></td>
                            <td><?= htmlspecialchars($admin['first_name']) ?></td>
                            <td><?= htmlspecialchars($admin['last_name']) ?></td>
                            <td>
                                <div class="action-dropdown">
                                    <button onclick="toggleDropdownAdmin(this)">⋮</button>
                                    <div class="dropdown-content">
                                        <a onclick='openEditPanelAdmin(<?= json_encode($admin['gmail']) ?>); return false;'>แก้ไข</a>
                                        <a onclick="deleteAdmin('<?= htmlspecialchars($admin['gmail']) ?>'); return false;">ลบ</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: gray; padding: 20px;">
                            ไม่มีข้อมูลผู้ดูแลระบบ
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div id="overlayAdmin" class="overlay" onclick="closeEditPanelAdmin()"></div>

        <div id="editPanelAdmin" class="side-panel">
            <div class="side-panel-content">
                <span class="side-panel-close-btn" onclick="closeEditPanelAdmin()">&times;</span>

                <div id="side-panel-form-content-admin">
                    <!-- ฟอร์มแก้ไขข้อมูลผู้ดูแลระบบ -->
                    <form id="editAdminForm" enctype="multipart/form-data">
                        <input type="hidden" id="edit_admin_id" name="id">

                        <div id="imagePreviewContainerAdmin">
                            <img id="imagePreviewAdmin" alt="รูปโปรไฟล์" style="width: 100px; height: 100px; border-radius: 30px;">
                        </div>

                        <label for="gmail_admin"><span class="material-icons">email</span> Gmail</label>
                        <input type="email" id="gmail_admin" name="gmail" required>

                        <label for="password_admin"><span class="material-icons">lock</span> Password</label>
                        <input type="password" id="password_admin" name="password" placeholder="กรอกถ้าต้องการเปลี่ยน">

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="first_name_admin"><span class="material-icons">badge</span> ชื่อจริง</label>
                                <input type="text" id="first_name_admin" name="first_name" required>
                            </div>
                            <div class="group-item">
                                <label for="last_name_admin"><span class="material-icons">badge</span> นามสกุล</label>
                                <input type="text" id="last_name_admin" name="last_name" required>
                            </div>
                        </div>
                        <button type="submit">บันทึกการแก้ไข</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="confirmDeleteAdminModal" class="custom-modal hidden">
        <div class="custom-modal-content">
            <span class="material-icons">feedback</span>
            <p id="confirmDeleteAdminMessage">คุณแน่ใจหรือไม่ว่าต้องการลบผู้ดูแลระบบนี้?</p>
            <div class="modal-actions">
                <button id="btnConfirmDeleteAdmin" class="btn-Confirm btn-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                    </svg>
                    ลบผู้ดูแลระบบ
                </button>
                <button id="btnCancelDeleteAdmin" class="btn-Confirm btn-secondary">ยกเลิก</button>
            </div>
        </div>
    </div>


    <div id="employee" class="content">
        <h2 class="h-text-upload">การจัดการพนักงาน
            <p>Employee Management</p>
        </h2>
        <!-- ตารางข้อมูลผู้ใช้ -->
        <!-- <table>
            <thead>
                <tr>
                    <th style="width: 150px;">รูปโปรไฟล์</th>
                    <th style="width: 30%;">Gmail</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td>
                            <?php if ($admin['profile_image']): ?>
                                <img src="<?= $admin['profile_image'] ?>" width="80" height="auto" alt="Profile">
                            <?php else: ?>
                                ไม่มีภาพ
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($admin['gmail']) ?></td>
                        <td><?= htmlspecialchars($admin['first_name']) ?></td>
                        <td><?= htmlspecialchars($admin['last_name']) ?></td>
                        <td>
                            <div class="action-dropdown">
                                <button onclick="toggleDropdownAdmin(this)">⋮</button>
                                <div class="dropdown-content">
                                    <a href="#" onclick="openEditPanelAdmin('<?= htmlspecialchars($admin['gmail']) ?>'); return false;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg> แก้ไข
                                    </a>
                                    <a href="#" onclick="deleteAdmin('<?= htmlspecialchars($admin['gmail']) ?>'); return false;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg> ลบ
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table> -->
        <div class="center-screen">
            <h1>ยังไม่เปิดใช้งาน</h1>
        </div>
    </div>

    <div id="account" class="content">
        <div class="profile-container">
            <div class="cover"></div>
            <div class="profile-card">
                <div class="profile-image">
                    <img id="profile-pic"
                        src="<?= !empty($currentAdmin['profile_image']) ? $currentAdmin['profile_image'] : '/sci-next/img/default.jpg' ?>"
                        alt="Admin Profile">
                    <label for="file-input" class="upload-btn">
                        <i class="material-icons">photo_camera</i>
                    </label>
                    <input id="file-input" type="file" name="profile_image" accept="image/*" onchange="previewImage(event)">
                </div>

                <div class="profile-details">
                    <form id="profile-form" method="POST" enctype="multipart/form-data" action="updateProfile/updateProfileSuperadmin.php">
                        <h2 class="profile-title">ข้อมูลโปรไฟล์</h2>

                        <div class="profile-row">
                            <div class="profile-info">
                                <label><span class="material-icons">badge</span> ชื่อ</label>
                                <input type="text" name="first_name" id="first-name-adminProfile"
                                    value="<?= htmlspecialchars($currentAdmin['first_name']) ?>" required>
                            </div>
                            <div class="profile-info">
                                <label><span class="material-icons">fingerprint</span> นามสกุล</label>
                                <input type="text" name="last_name" id="last-name-adminProfile"
                                    value="<?= htmlspecialchars($currentAdmin['last_name']) ?>" required>
                            </div>
                        </div>

                        <div class="profile-row">
                            <div class="profile-info">
                                <label>
                                    <span class="material-icons">person_outline</span> อีเมล
                                </label>

                                <div class="email-input-wrapper">
                                    <input
                                        type="text"
                                        name="gmail"
                                        id="username-adminProfile"
                                        value="<?= htmlspecialchars(maskEmail($currentAdmin['gmail'])) ?>"
                                        data-masked-email="<?= htmlspecialchars(maskEmail($currentAdmin['gmail'])) ?>"
                                        required />
                                    <input type="hidden" id="real-gmail" value="<?= htmlspecialchars($currentAdmin['gmail']) ?>">

                                    <button
                                        type="button"
                                        id="toggleEmailBtn"
                                        class="toggle-email-btn"
                                        onclick="toggleEmailVisibility()">
                                        <span class="material-icons" id="emailEyeIcon">visibility_off</span>
                                    </button>
                                </div>
                            </div>
                            <div class="profile-info">
                                <label><span class="material-icons">password</span> รหัสผ่าน (ใหม่)</label>
                                <input type="password" name="password" id="password-adminProfile" placeholder="********">
                            </div>
                        </div>

                        <div class="profile-row">
                            <button type="submit" class="confirm-btn">
                                ยืนยันการแก้ไข
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let toastTimeoutId = null;

        const icons = {
            empty: `
        <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20" fill="#E57300">
        <path d="M600-240v-120H488q-32 54-87 87t-121 33q-100 0-170-70T40-480q0-100 70-170t170-70q66 0 121 33t87 87h272v80H434q-8-39-48-79.5T280-640q-66 0-113 47t-47 113q0 66 47 113t113 47q66 0 106-40.5t48-79.5h246v120h80v80H600Z"/>
        </svg>`,
            select: `
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#E57300">
        <path d="M516-82q-9 2-18 2h-18q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480v18q0 9-2 18l-78-24v-12q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93h12l24 78Zm305 22L650-231 600-80 480-480l400 120-151 50 171 171-79 79Z"/>
        </svg>`,
            success: `
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#4CAF50"><path d="m562-225 199-199-57-56-142 142-56-57-57 57 113 113ZM280-360h120v-80H280v80Zm0-120h280v-80H280v80Zm0-120h280v-80H280v80ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>`,
            error: `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#DC143C"><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg> `,
        };

        function showAlertToast(message, icon = "", customClass = "") {
            const toast = document.getElementById("alertToast");
            const text = document.getElementById("alertToastMessage");
            const iconSpan = document.getElementById("alertToastIcon");

            if (!toast || !text || !iconSpan) return;

            // เคลียร์ timeout และรีเซ็ตก่อน
            if (toastTimeoutId) {
                clearTimeout(toastTimeoutId);
                toast.style.display = "none";
                toast.style.animation = "none";
                toast.offsetHeight;
            }

            // ล้าง class ก่อน
            toast.className = "alert-toast"; // class base
            if (customClass) toast.classList.add(customClass); // เพิ่ม class เฉพาะ

            iconSpan.innerHTML = icon;
            text.textContent = message;

            toast.style.display = "flex";
            toast.style.animation = "bounceIn 0.4s ease, fadeOut 4s ease-in-out forwards";

            toastTimeoutId = setTimeout(() => {
                toast.style.display = "none";
                toastTimeoutId = null;
            }, 4000);
        }

        function previewImage(event) {
            const input = event.target;
            const reader = new FileReader();

            reader.onload = function() {
                const imgElement = document.getElementById('profile-pic');
                imgElement.src = reader.result;
            };

            if (input.files.length > 0) {
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('upload-img-admin').addEventListener('change', function() {
            const fileInput = this;
            const fileNameSpan = document.getElementById('file-name');
            const file = fileInput.files[0];

            if (!file) {
                fileNameSpan.textContent = '';
                return;
            }

            const maxSize = 1 * 1024 * 1024; // 1MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

            // ตรวจสอบขนาด
            if (file.size > maxSize) {
                fileNameSpan.textContent = 'ไฟล์ใหญ่เกิน 1MB';
                fileNameSpan.style.color = '#DC143C';
                fileInput.value = ''; // ล้างค่า input
                return;
            }

            // ตรวจสอบประเภทไฟล์
            if (!allowedTypes.includes(file.type)) {
                fileNameSpan.textContent = 'ประเภทไฟล์ไม่รองรับ (รองรับ: JPG, PNG, WEBP)';
                fileNameSpan.style.color = '#DC143C';
                fileInput.value = ''; // ล้างค่า input
                return;
            }

            // ถ้าผ่านทั้งหมด
            fileNameSpan.textContent = file.name;
            fileNameSpan.style.color = '#37B9F1';
        });

        document.getElementById('upload-img-employee').addEventListener('change', function() {
            const fileInput = this;
            const fileNameSpan = document.getElementById('file-name');
            const file = fileInput.files[0];

            if (!file) {
                fileNameSpan.textContent = '';
                return;
            }

            const maxSize = 1 * 1024 * 1024; // 1MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

            // ตรวจสอบขนาด
            if (file.size > maxSize) {
                fileNameSpan.textContent = 'ไฟล์ใหญ่เกิน 1MB';
                fileNameSpan.style.color = '#DC143C';
                fileInput.value = ''; // ล้างค่า input
                return;
            }

            // ตรวจสอบประเภทไฟล์
            if (!allowedTypes.includes(file.type)) {
                fileNameSpan.textContent = 'ประเภทไฟล์ไม่รองรับ (รองรับ: JPG, PNG, WEBP)';
                fileNameSpan.style.color = '#DC143C';
                fileInput.value = ''; // ล้างค่า input
                return;
            }

            // ถ้าผ่านทั้งหมด
            fileNameSpan.textContent = file.name;
            fileNameSpan.style.color = '#333';
        });


        document.getElementById("profile-form").addEventListener("submit", function(event) {
            event.preventDefault(); // ป้องกันการรีโหลดหน้า

            const firstName = document.getElementById("first-name").value;
            const lastName = document.getElementById("last-name").value;
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            // ส่งข้อมูลไปบันทึก (เช่น ส่งไป API หรืออัปเดตฐานข้อมูล)
            alert("ข้อมูลได้รับการอัปเดตเรียบร้อย!");
        });

        // ฟังก์ชันแสดงชื่อไฟล์ที่เลือก Excal
        function showFileName(input) {
            const fileNameText = input.nextElementSibling;
            if (input.files.length > 0) {
                fileNameText.textContent = input.files[0].name;
            } else {
                fileNameText.textContent = "ยังไม่ได้เลือกไฟล์";
            }
        }

        // กราฟภาพรวมยอดขาย (bar chart)
        const mainSalesCtx = document.getElementById('mainSalesChart').getContext('2d');
        const mainSalesChart = new Chart(mainSalesCtx, {
            type: 'line',
            data: {
                labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
                datasets: [{
                    label: 'ยอดขาย (บาท)',
                    data: [120000, 135000, 110000, 150000, 175000, 160000, 170000, 180000, 155000, 165000, 190000, 200000],
                    borderColor: '#8739F9',
                    backgroundColor: 'rgba(135, 57, 249, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#8739F9',
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        display: false // ถ้าไม่อยากโชว์ scales ให้ปิดแบบนี้
                    },
                    x: {
                        ticks: {
                            color: '#908E9B',
                            font: {
                                family: 'Noto Sans Thai',
                                size: 14,
                                weight: '600'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false,
                        labels: {
                            font: {
                                family: 'Noto Sans Thai',
                                size: 16
                            }
                        }
                    }
                }
            }
        });



        function toggleDropdown(button) {
            // ปิด dropdown อื่นก่อน
            document.querySelectorAll('.action-dropdown').forEach(dropdown => {
                if (dropdown !== button.parentElement) {
                    dropdown.classList.remove('show');
                }
            });

            // toggle อันที่คลิก
            button.parentElement.classList.toggle('show');
        }

        function toggleDropdownSoftDrink(button) {
            // ปิด dropdown อื่นก่อน
            document.querySelectorAll('.action-dropdown-soft-drink').forEach(dropdown => {
                if (dropdown !== button.parentElement) {
                    dropdown.classList.remove('show');
                }
            });

            // toggle อันที่คลิก
            button.parentElement.classList.toggle('show');
        }

        function toggleDropdownFreshFood(button) {
            // ปิด dropdown อื่นก่อน
            document.querySelectorAll('.action-dropdown-fresh-food').forEach(dropdown => {
                if (dropdown !== button.parentElement) {
                    dropdown.classList.remove('show');
                }
            });

            // toggle อันที่คลิก
            button.parentElement.classList.toggle('show');
        }

        function toggleDropdownSnackFood(button) {
            // ปิด dropdown อื่นก่อน
            document.querySelectorAll('.action-dropdown-snack').forEach(dropdown => {
                if (dropdown !== button.parentElement) {
                    dropdown.classList.remove('show');
                }
            });

            // toggle อันที่คลิก
            button.parentElement.classList.toggle('show');
        }

        function toggleDropdownStationery(button) {
            // ปิด dropdown อื่นก่อน
            document.querySelectorAll('.action-dropdown-stationery').forEach(dropdown => {
                if (dropdown !== button.parentElement) {
                    dropdown.classList.remove('show');
                }
            });

            // toggle อันที่คลิก
            button.parentElement.classList.toggle('show');
        }

        function toggleDropdownAdmin(button) {
            // ปิด dropdown อื่นก่อน
            document.querySelectorAll('.action-dropdown-admin').forEach(dropdown => {
                if (dropdown !== button.parentElement) {
                    dropdown.classList.remove('show');
                }
            });

            // toggle อันที่คลิก
            button.parentElement.classList.toggle('show');
        }

        // ปิด dropdown เมื่อคลิกนอก
        window.addEventListener('click', function(e) {
            if (!e.target.matches('.action-dropdown button')) {
                document.querySelectorAll('.action-dropdown').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });

        window.addEventListener('click', function(e) {
            if (!e.target.matches('.action-dropdown-soft-drink button')) {
                document.querySelectorAll('.action-dropdown-soft-drink').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });

        window.addEventListener('click', function(e) {
            if (!e.target.matches('.action-dropdown-fresh-food button')) {
                document.querySelectorAll('.action-dropdown-fresh-food').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });

        window.addEventListener('click', function(e) {
            if (!e.target.matches('.action-dropdown-snack button')) {
                document.querySelectorAll('.action-dropdown-snack').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });

        window.addEventListener('click', function(e) {
            if (!e.target.matches('.action-dropdown-stationery button')) {
                document.querySelectorAll('.action-dropdown-stationery').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });

        window.addEventListener('click', function(e) {
            if (!e.target.matches('.action-dropdown-admin button')) {
                document.querySelectorAll('.action-dropdown-admin').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });

        document.getElementById('editProductForm').addEventListener('submit', function(e) {
            e.preventDefault(); // ป้องกัน submit แบบเปลี่ยนหน้า

            const form = e.target;
            const formData = new FormData(form);

            fetch('../../product/edit_product/edit_food_all/edit_dried_food.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlertToast('แก้ไขข้อมูลสินค้าเรียบร้อยแล้ว', icons.success, 'success');
                        setTimeout(() => location.reload(), 1000);
                        // ปิด panel
                        document.getElementById('editPanel').style.right = '-100%';
                        document.getElementById('overlay').style.display = 'none';
                        document.body.style.overflow = '';
                    } else {
                        showAlertToast('เกิดข้อผิดพลาด: ' + data.message, icons.error, 'alert-toast');
                    }
                })
                .catch(error => {
                    console.error('เกิดข้อผิดพลาด:', error);
                    showAlertToast('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์', icons.error, 'alert-toast');
                });
        });

        function openEditPanel(id) {
            const overlay = document.getElementById('overlay');
            const editPanel = document.getElementById('editPanel');

            if (overlay && editPanel) {
                overlay.style.display = 'block';
                editPanel.style.display = 'block';
                setTimeout(() => {
                    editPanel.style.right = '0';
                }, 10);
                document.body.style.overflow = 'hidden';

                // ใช้ fetch เพื่อดึงข้อมูลสินค้าตาม id ที่ส่งมา
                fetch('../../product/edit_product/get_food_all/get_dried_food.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit_product_id').value = data.id;
                        document.getElementById('imagePreview').src = data.image_url;
                        document.getElementById('product_name').value = data.product_name;
                        document.getElementById('barcodeDriedFood').value = data.barcode || '';
                        document.getElementById('price').value = data.price;
                        document.getElementById('cost').value = data.cost;
                        document.getElementById('stock').value = data.stock;
                        document.getElementById('reorder_level').value = data.reorder_level;
                    })
                    .catch(error => {
                        console.error('เกิดข้อผิดพลาดในการโหลดข้อมูลสินค้า:', error);
                    });
            } else {
                console.error("ไม่พบ overlay หรือ editPanel ใน DOM");
            }
        }

        function closeEditPanel() {
            const overlay = document.getElementById('overlay');
            const editPanel = document.getElementById('editPanel');

            overlay.style.display = 'none';
            editPanel.style.right = '-500px';
            setTimeout(() => {
                editPanel.style.display = 'none';
            }, 300); // ต้องรอให้ side panel เลื่อนออกไปก่อนค่อยปิด display

            // เปิดการเลื่อนหน้าจอเมื่อปิด editPanel
            document.body.style.overflow = 'auto';
        }

        document.getElementById('editProductFormSoftDrink').addEventListener('submit', function(e) {
            e.preventDefault(); // ป้องกันการเปลี่ยนหน้า

            const form = e.target;
            const formData = new FormData(form);

            fetch('../../product/edit_product/edit_food_all/edit_soft_drink.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlertToast('แก้ไขข้อมูลสินค้าเรียบร้อยแล้ว', icons.success, 'success');
                        setTimeout(() => location.reload(), 1000);
                        // ปิด panel
                        document.getElementById('editPanelSoftDrink').style.right = '-100%';
                        document.getElementById('overlaySoftDrink').style.display = 'none';
                        document.body.style.overflow = '';
                    } else {
                        showAlertToast('เกิดข้อผิดพลาด: ' + data.message, icons.error, 'alert-toast');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showAlertToast('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์', icons.error, 'alert-toast');
                });
        });

        function openEditPanelSoftDrink(id) {
            const overlay = document.getElementById('overlaySoftDrink');
            const editPanel = document.getElementById('editPanelSoftDrink');

            if (overlay && editPanel) {
                overlay.style.display = 'block';
                editPanel.style.display = 'block';
                setTimeout(() => {
                    editPanel.style.right = '0';
                }, 10);
                document.body.style.overflow = 'hidden';

                // ใช้ fetch เพื่อดึงข้อมูลสินค้าตาม id ที่ส่งมา
                fetch('../../product/edit_product/get_food_all/get_soft_drink.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit_product_id_softdrink').value = data.id;
                        document.getElementById('imagePreviewSoftDrink').src = data.image_url;
                        document.getElementById('product_name_softdrink').value = data.product_name;
                        document.getElementById('barcode_softdrink').value = data.barcode || '';
                        document.getElementById('price_softdrink').value = data.price;
                        document.getElementById('cost_softdrink').value = data.cost;
                        document.getElementById('stock_softdrink').value = data.stock;
                        document.getElementById('reorder_level_softdrink').value = data.reorder_level;
                    })
                    .catch(error => {
                        console.error('เกิดข้อผิดพลาดในการโหลดข้อมูลสินค้า:', error);
                    });
            } else {
                console.error("ไม่พบ overlay หรือ editPanel ใน DOM");
            }
        }


        function closeEditPanelSoftDrink() {
            const overlay = document.getElementById('overlaySoftDrink');
            const editPanel = document.getElementById('editPanelSoftDrink');

            overlay.style.display = 'none';
            editPanel.style.right = '-500px';
            setTimeout(() => {
                editPanel.style.display = 'none';
            }, 300); // ต้องรอให้ side panel เลื่อนออกไปก่อนค่อยปิด display

            // เปิดการเลื่อนหน้าจอเมื่อปิด editPanel
            document.body.style.overflow = 'auto';
        }


        document.getElementById('editProductFormFreshFood').addEventListener('submit', function(e) {
            e.preventDefault(); // ป้องกันการเปลี่ยนหน้า

            const form = e.target;
            const formData = new FormData(form);

            fetch('../../product/edit_product/edit_food_all/edit_fresh_food.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlertToast('แก้ไขข้อมูลสินค้าเรียบร้อยแล้ว', icons.success, 'success');
                        setTimeout(() => location.reload(), 1000);
                        // ปิด panel
                        document.getElementById('editPanelFreshFood').style.right = '-100%';
                        document.getElementById('overlayFreshFood').style.display = 'none';
                        document.body.style.overflow = '';
                    } else {
                        showAlertToast('เกิดข้อผิดพลาด: ' + data.message, icons.error, 'alert-toast');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showAlertToast('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์', icons.error, 'alert-toast');
                });
        });

        function openEditPanelFreshFood(id) {
            const overlay = document.getElementById('overlayFreshFood');
            const editPanel = document.getElementById('editPanelFreshFood');

            if (overlay && editPanel) {
                overlay.style.display = 'block';
                editPanel.style.display = 'block';
                setTimeout(() => {
                    editPanel.style.right = '0';
                }, 10);
                document.body.style.overflow = 'hidden';

                // ใช้ fetch เพื่อดึงข้อมูลสินค้าตาม id ที่ส่งมา
                fetch('../../product/edit_product/get_food_all/get_fresh_food.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit_product_id_freshfood').value = data.id;
                        document.getElementById('imagePreviewFreshFood').src = data.image_url;
                        document.getElementById('product_name_freshfood').value = data.product_name;
                        document.getElementById('barcode_freshfood').value = data.barcode || '';
                        document.getElementById('price_freshfood').value = data.price;
                        document.getElementById('cost_freshfood').value = data.cost;
                        document.getElementById('stock_freshfood').value = data.stock;
                        document.getElementById('reorder_level_freshfood').value = data.reorder_level;
                    })
                    .catch(error => {
                        console.error('เกิดข้อผิดพลาดในการโหลดข้อมูลสินค้า:', error);
                    });
            } else {
                console.error("ไม่พบ overlay หรือ editPanel ใน DOM");
            }
        }

        function closeEditPanelFreshFood() {
            const overlay = document.getElementById('overlayFreshFood');
            const editPanel = document.getElementById('editPanelFreshFood');

            overlay.style.display = 'none';
            editPanel.style.right = '-500px';
            setTimeout(() => {
                editPanel.style.display = 'none';
            }, 300); // ต้องรอให้ side panel เลื่อนออกไปก่อนค่อยปิด display

            // เปิดการเลื่อนหน้าจอเมื่อปิด editPanel
            document.body.style.overflow = 'auto';
        }

        document.getElementById('editProductFormSnackFood').addEventListener('submit', function(e) {
            e.preventDefault(); // ป้องกันการเปลี่ยนหน้า

            const form = e.target;
            const formData = new FormData(form);

            fetch('../../product/edit_product/edit_food_all/edit_snack.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlertToast('แก้ไขข้อมูลสินค้าเรียบร้อยแล้ว', icons.success, 'success');
                        setTimeout(() => location.reload(), 1000);
                        // ปิด panel
                        document.getElementById('editPanelSnackFood').style.right = '-100%';
                        document.getElementById('overlaySnackFood').style.display = 'none';
                        document.body.style.overflow = '';
                    } else {
                        showAlertToast('เกิดข้อผิดพลาด: ' + data.message, icons.error, 'alert-toast');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showAlertToast('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์', icons.error, 'alert-toast');
                });
        });

        function openEditPanelSnackFood(id) {
            const overlay = document.getElementById('overlaySnackFood');
            const editPanel = document.getElementById('editPanelSnackFood');

            if (overlay && editPanel) {
                overlay.style.display = 'block';
                editPanel.style.display = 'block';
                setTimeout(() => {
                    editPanel.style.right = '0';
                }, 10);
                document.body.style.overflow = 'hidden';

                // ใช้ fetch เพื่อดึงข้อมูลสินค้าตาม id ที่ส่งมา
                fetch('../../product/edit_product/get_food_all/get_snack.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        // แสดงข้อมูลในฟอร์ม
                        document.getElementById('edit_product_id_snackfood').value = data.id;
                        document.getElementById('imagePreviewSnackFood').src = data.image_url;
                        document.getElementById('product_name_snackfood').value = data.product_name;
                        document.getElementById('barcode_snackfood').value = data.barcode || '';
                        document.getElementById('price_snackfood').value = data.price;
                        document.getElementById('cost_snackfood').value = data.cost;
                        document.getElementById('stock_snackfood').value = data.stock;
                        document.getElementById('reorder_level_snackfood').value = data.reorder_level;
                    })
                    .catch(error => {
                        console.error('เกิดข้อผิดพลาดในการโหลดข้อมูลสินค้า:', error);
                    });
            } else {
                console.error("ไม่พบ overlay หรือ editPanel ใน DOM");
            }
        }

        function closeEditPanelSnackFood() {
            const overlay = document.getElementById('overlaySnackFood');
            const editPanel = document.getElementById('editPanelSnackFood');

            overlay.style.display = 'none';
            editPanel.style.right = '-500px';
            setTimeout(() => {
                editPanel.style.display = 'none';
            }, 300); // ต้องรอให้ side panel เลื่อนออกไปก่อนค่อยปิด display

            // เปิดการเลื่อนหน้าจอเมื่อปิด editPanel
            document.body.style.overflow = 'auto';
        }

        document.getElementById('editProductFormStationery').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            try {
                const response = await fetch('../../product/edit_product/edit_food_all/edit_stationery.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showAlertToast('แก้ไขข้อมูลสินค้าเรียบร้อยแล้ว', icons.success, 'success');

                    setTimeout(() => {
                        location.reload();
                    }, 1000);

                    // ปิด panel
                    document.getElementById('editPanelStationery').style.right = '-100%';
                    document.getElementById('overlayStationery').style.display = 'none';
                    document.body.style.overflow = '';
                } else {
                    showAlertToast('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่ทราบสาเหตุ'), icons.error, 'alert-toast');
                }
            } catch (err) {
                console.error('Error:', err);
                showAlertToast('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์', icons.error, 'alert-toast');
            }
        });

        function openEditPanelAdmin(gmail) {

            const overlay = document.getElementById('overlayAdmin');
            const editPanel = document.getElementById('editPanelAdmin');

            if (!overlay || !editPanel) {
                console.error('ไม่พบ overlayAdmin หรือ editPanelAdmin ใน DOM');
                return;
            }

            // แสดง overlay และ panel แล้วเลื่อน panel เข้ามา
            overlay.style.display = 'block';
            editPanel.style.display = 'block';
            setTimeout(() => {
                editPanel.style.right = '0';
            }, 10);

            // ปิด scroll หน้าเว็บขณะเปิด panel
            document.body.style.overflow = 'hidden';

            // โหลดข้อมูล admin ผ่าน fetch
            fetch(`manageAdmin/getAdminData.php?gmail=${encodeURIComponent(gmail)}`)
                .then(res => {
                    if (!res.ok) {
                        throw new Error('ไม่สามารถโหลดข้อมูลผู้ดูแลระบบได้');
                    }
                    return res.json();
                })
                .then(data => {

                    if (data.error) {
                        alert(data.error);
                        closeEditPanelAdmin();
                        return;
                    }

                    document.getElementById('edit_admin_id').value = data.id || '';
                    document.getElementById('gmail_admin').value = data.gmail || '';
                    document.getElementById('password_admin').value = '';
                    document.getElementById('first_name_admin').value = data.first_name || '';
                    document.getElementById('last_name_admin').value = data.last_name || '';

                    if (data.profile_image) {
                        document.getElementById('imagePreviewAdmin').src = data.profile_image;
                    } else {
                        document.getElementById('imagePreviewAdmin').src = '/sci-next/img/default.jpg';
                    }
                })
                .catch(err => {
                    console.error('Error loading admin data:', err);
                    alert('ไม่สามารถโหลดข้อมูลผู้ดูแลระบบได้');
                    closeEditPanelAdmin();
                });
        }

        function closeEditPanelAdmin() {
            const overlay = document.getElementById('overlayAdmin');
            const editPanel = document.getElementById('editPanelAdmin');

            if (!overlay || !editPanel) {
                console.error('ไม่พบ overlayAdmin หรือ editPanelAdmin ใน DOM');
                return;
            }

            // เลื่อน panel ออกไปทางขวา
            editPanel.style.right = '-500px'; // ปรับตามความกว้าง panel จริง

            // ซ่อน overlay และ panel หลังแอนิเมชันเสร็จ (300ms)
            setTimeout(() => {
                overlay.style.display = 'none';
                editPanel.style.display = 'none';
            }, 300);

            // เปิด scroll หน้าเว็บกลับ
            document.body.style.overflow = '';
        }

        function previewAdminImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreviewAdmin').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        document.getElementById('editAdminForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const emailInput = document.getElementById("username-adminProfile");

            // ถ้าผู้ใช้ยังเห็นเป็น masked email ให้ใส่ค่า email จริงก่อน
            if (emailInput.value === maskedEmail) {
                emailInput.value = originalEmail;
            }

            // ✅ ต้องสร้าง FormData หลังเปลี่ยนค่าแล้ว!
            const formData = new FormData(form);

            try {
                const response = await fetch('manageAdmin/updateAdmin.php', {
                    method: 'POST',
                    body: formData
                });

                const text = await response.text();

                try {
                    const data = JSON.parse(text);

                    if (response.ok && data.success) {
                        showAlertToast('บันทึกข้อมูลผู้ดูแลระบบสำเร็จแล้ว', icons.success, 'success');
                        setTimeout(() => location.reload(), 1000);
                        document.getElementById('editPanelAdmin').style.right = '-100%';
                        document.getElementById('overlayAdmin').style.display = 'none';
                        document.body.style.overflow = '';
                    } else {
                        showAlertToast('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่ทราบสาเหตุ'), icons.error, 'alert-toast');
                    }
                } catch (jsonErr) {
                    console.error('Response is not valid JSON:', text);
                    showAlertToast('เกิดข้อผิดพลาดจากเซิร์ฟเวอร์ (ข้อมูลไม่ถูกต้อง)', icons.error, 'alert-toast');
                }
            } catch (err) {
                console.error('Fetch Error:', err);
                showAlertToast('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์', icons.error, 'alert-toast');
            }
        });

        function filterAdminsByGmail() {
            const input = document.getElementById("searchAdminInput").value.toLowerCase();
            const tbody = document.getElementById("adminTableBody");
            const rows = tbody.getElementsByTagName("tr");
            let found = false;

            for (let row of rows) {
                const gmailCell = row.getElementsByTagName("td")[1];
                if (gmailCell) {
                    const gmailText = gmailCell.textContent.toLowerCase();
                    const match = gmailText.includes(input);
                    row.style.display = match ? "" : "none";
                    if (match) found = true;
                }
            }

            // ลบแถว "ไม่พบข้อมูลผู้ดูแล" เดิมถ้ามี
            const existingEmptyRow = document.getElementById("noAdminFoundRow");
            if (existingEmptyRow) {
                tbody.removeChild(existingEmptyRow);
            }

            if (!found) {
                const noDataRow = document.createElement("tr");
                noDataRow.id = "noAdminFoundRow";
                noDataRow.innerHTML = `<td colspan="5" style="text-align: center; color: gray; padding: 20px;">ไม่พบข้อมูลผู้ดูแลระบบ</td>`;
                tbody.appendChild(noDataRow);
            }
        }

        function deleteAdmin(gmail) {
            const modal = document.getElementById("confirmDeleteAdminModal");
            const message = document.getElementById("confirmDeleteAdminMessage");
            const confirmBtn = document.getElementById("btnConfirmDeleteAdmin");
            const cancelBtn = document.getElementById("btnCancelDeleteAdmin");

            message.textContent = "คุณแน่ใจหรือไม่ว่าต้องการลบผู้ดูแลระบบนี้?";
            modal.classList.remove("hidden");

            // รีเซ็ต event listener ป้องกันซ้ำ
            confirmBtn.replaceWith(confirmBtn.cloneNode(true));
            cancelBtn.replaceWith(cancelBtn.cloneNode(true));

            const newConfirmBtn = document.getElementById("btnConfirmDeleteAdmin");
            const newCancelBtn = document.getElementById("btnCancelDeleteAdmin");

            newConfirmBtn.addEventListener("click", () => {
                modal.classList.add("hidden");

                fetch("manageAdmin/deleteAdmin.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `gmail=${encodeURIComponent(gmail)}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showAlertToast(data.message || "ลบผู้ดูแลระบบเรียบร้อยแล้ว", icons.success, "success-toast");
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showAlertToast(data.message || "เกิดข้อผิดพลาด", icons.error, "alert-toast");
                        }
                    })
                    .catch(err => {
                        console.error("Delete failed:", err);
                        showAlertToast("ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้", icons.error, "alert-toast");
                    });
            });

            newCancelBtn.addEventListener("click", () => {
                modal.classList.add("hidden");
            });

            window.addEventListener("click", (event) => {
                if (event.target === modal) {
                    modal.classList.add("hidden");
                }
            });
        }


        async function openEditPanelStationery(id) {
            const overlay = document.getElementById('overlayStationery');
            const editPanel = document.getElementById('editPanelStationery');

            if (!overlay || !editPanel) {
                console.error("ไม่พบ overlay หรือ editPanel ใน DOM");
                return;
            }

            overlay.style.display = 'block';
            editPanel.style.display = 'block';
            setTimeout(() => {
                editPanel.style.right = '0';
            }, 10);
            document.body.style.overflow = 'hidden';

            try {
                const response = await fetch(`../../product/edit_product/get_food_all/get_stationery.php?id=${id}`);
                const data = await response.json();

                if (!response.ok) throw new Error(data.message || 'เกิดข้อผิดพลาดในการโหลดข้อมูล');

                // แสดงข้อมูลในฟอร์ม
                document.getElementById('edit_product_id_stationery').value = data.id || '';
                document.getElementById('product_name_stationery').value = data.product_name || '';
                document.getElementById('barcode_stationery').value = data.barcode || '';
                document.getElementById('price_stationery').value = data.price || '';
                document.getElementById('cost_stationery').value = data.cost || '';
                document.getElementById('stock_stationery').value = data.stock || '';
                document.getElementById('reorder_level_stationery').value = data.reorder_level || '';

                const imagePreview = document.getElementById('imagePreviewStationery');
                imagePreview.src = data.image_url ? data.image_url : 'path/to/default-image.jpg';

            } catch (error) {
                console.error('เกิดข้อผิดพลาดในการโหลดข้อมูลสินค้า:', error);
                showAlertToast('ไม่สามารถโหลดข้อมูลสินค้าได้', icons.error, 'alert-toast');
            }
        }

        function closeEditPanelStationery() {
            const overlay = document.getElementById('overlayStationery');
            const editPanel = document.getElementById('editPanelStationery');

            if (!overlay || !editPanel) return;

            overlay.style.display = 'none';
            editPanel.style.right = '-500px';

            setTimeout(() => {
                editPanel.style.display = 'none';
            }, 300);

            document.body.style.overflow = 'auto';
        }


        function deleteProduct(id, type) {
            const modal = document.getElementById("confirmDeleteProductModal");
            const message = document.getElementById("confirmDeleteMessage");
            const confirmBtn = document.getElementById("btnConfirmDeleteProduct");
            const cancelBtn = document.getElementById("btnCancelDeleteProduct");

            const endpoints = {
                dried: "/sci-next/product/delete_product/delete_dried_food.php",
                soft: "/sci-next/product/delete_product/delete_soft_drink.php",
                fresh: "/sci-next/product/delete_product/delete_fresh_food.php",
                snack: "/sci-next/product/delete_product/delete_snack.php",
                stationery: "/sci-next/product/delete_product/delete_stationery.php",
            };

            // ตรวจสอบว่าประเภทสินค้ามี endpoint รองรับ
            if (!endpoints[type]) {
                console.error("ไม่พบประเภทสินค้า:", type);
                showAlertToast("ประเภทสินค้าที่เลือกไม่ถูกต้อง", icons.error, "alert-toast");
                return;
            }

            message.textContent = "คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?";
            modal.classList.remove("hidden");

            // รีเซ็ต event listener
            confirmBtn.replaceWith(confirmBtn.cloneNode(true));
            cancelBtn.replaceWith(cancelBtn.cloneNode(true));

            const newConfirmBtn = document.getElementById("btnConfirmDeleteProduct");
            const newCancelBtn = document.getElementById("btnCancelDeleteProduct");

            newConfirmBtn.addEventListener("click", () => {
                modal.classList.add("hidden");

                fetch(endpoints[type], {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `id=${encodeURIComponent(id)}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showAlertToast(data.message, icons.success, "success-toast");
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showAlertToast(data.message, icons.error, "alert-toast");
                        }
                    })
                    .catch(err => {
                        console.error("Delete failed:", err);
                        showAlertToast("ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้", icons.error, "alert-toast");
                    });
            });

            newCancelBtn.addEventListener("click", () => {
                modal.classList.add("hidden");
            });

            window.addEventListener("click", (event) => {
                if (event.target === modal) {
                    modal.classList.add("hidden");
                }
            });
        }


        function toggleMenu() {
            const filterMenu = document.getElementById('filterMenu');
            filterMenu.style.display = (filterMenu.style.display === 'block') ? 'none' : 'block';
        }

        function applyFilters() {
            const category = document.getElementById('filterCategory').value;
            const price = document.getElementById('filterPrice').value;

            console.log(`Applied Filters - Category: ${category}, Max Price: ${price}`);
        }

        function toggleMenuSoftDrink() {
            const filterMenu = document.getElementById('filterMenuSoftDrink');
            filterMenu.style.display = (filterMenu.style.display === 'block') ? 'none' : 'block';
        }

        function applyFiltersSoftDrink() {
            const category = document.getElementById('filterCategorySoftDrink').value;
            const price = document.getElementById('filterPrice').value;

            console.log(`Applied Filters - Category: ${category}, Max Price: ${price}`);
        }

        function toggleMenuFreshFood() {
            const filterMenu = document.getElementById('filterMenuFreshFood');
            filterMenu.style.display = (filterMenu.style.display === 'block') ? 'none' : 'block';
        }

        function applyFiltersFreshFood() {
            const category = document.getElementById('filterCategoryFreshFood').value;
            const price = document.getElementById('filterPrice').value;

            console.log(`Applied Filters - Category: ${category}, Max Price: ${price}`);
        }

        function toggleMenuSnackFood() {
            const filterMenu = document.getElementById('filterMenuSnackFood');
            filterMenu.style.display = (filterMenu.style.display === 'block') ? 'none' : 'block';
        }

        function applyFiltersSnackFood() {
            const category = document.getElementById('filterCategorySnackFood').value;
            const price = document.getElementById('filterPrice').value;

            console.log(`Applied Filters - Category: ${category}, Max Price: ${price}`);
        }

        function toggleMenuStationery() {
            const filterMenu = document.getElementById('filterMenuStationery');
            filterMenu.style.display = (filterMenu.style.display === 'block') ? 'none' : 'block';
        }

        function applyFiltersStationery() {
            const category = document.getElementById('filterCategoryStationery').value;
            const price = document.getElementById('filterPrice').value;

            console.log(`Applied Filters - Category: ${category}, Max Price: ${price}`);
        }


        const products = <?php echo json_encode($dried_food); ?>;
        const tableBody = document.getElementById('productTableBody');
        const grid = document.getElementById('productGrid');
        const pagination = document.getElementById('pagination');
        const container = document.getElementById('container');

        let filteredProducts = [...products];
        let currentPage = 1;
        const itemsPerPage = 10;
        let searchText = '';
        let currentView = localStorage.getItem('viewMode') || 'table'; // ใช้ค่าใน localStorage หรือ default เป็น table

        function highlightText(text, keyword) {
            if (!keyword) return text;
            const pattern = new RegExp(`(${keyword})`, 'gi');
            return text.replace(pattern, '<span class="highlight">$1</span>');
        }

        function displayProducts() {
            tableBody.innerHTML = '';
            grid.innerHTML = '';

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const paginatedItems = filteredProducts.slice(startIndex, endIndex);

            if (currentView === 'table') {
                document.getElementById('productTable').style.display = 'table';
                grid.style.display = 'none';

                if (paginatedItems.length === 0) {
                    tableBody.innerHTML = `
        <tr>
        <td colspan="8" class="no-items-message">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
            ไม่พบสินค้าที่ค้นหา
        </td>
        </tr>`;
                } else {
                    paginatedItems.forEach(item => {
                        tableBody.innerHTML += `
                    <tr>
                        <td style="text-align:left; font-weight: bold;">${highlightText(item.product_name, searchText)}</td>
                        <td>
                            <div class="product-image-container">
                                <img src="${item.image_url}" alt="${item.product_name}" width="100">
                                ${item.stock <= item.reorder_level ? `
                                <div class="out-of-stock-label-table">สินค้าใกล้หมด</div>
                                ` : ''}
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <div class="barcode-cell">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg>
                                <span>${highlightText(item.barcode, searchText)}</span>
                            </div>
                        </td>
                        <td>${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} บาท</td>
                        <td>${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} บาท</td>
                        <td>${item.stock} ชิ้น</td>
                        <td>${item.reorder_level} ชิ้น</td>
                        <td>
                            <div class="action-dropdown">
                                <button onclick="toggleDropdown(this)">⋮</button>
                                <div class="dropdown-content">
                                    <a onclick="openEditPanel(${item.id}); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</a>
                                    <a onclick="deleteProduct(${item.id}, 'dried'); return false;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                                    </svg>
                                    ลบ
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
                    });
                }
            } else if (currentView === 'grid') {
                document.getElementById('productTable').style.display = 'none';
                grid.style.display = 'grid';

                if (paginatedItems.length === 0) {
                    grid.innerHTML = `
        <p style="grid-column: 1 / -1; text-align: center; display: flex; align-items: center; justify-content: center; gap: 2px; ">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333" style="margin-right: 10px;">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
            ไม่พบสินค้าที่ค้นหา
        </p>
        `;
                } else {
                    paginatedItems.forEach(item => {
                        grid.innerHTML += `
                    <div class="card">
                        <div class="product-image-container">
                            <img class="product-image" src="${item.image_url}" alt="${item.product_name}">
                            ${item.stock <= item.reorder_level ? `
                            <div class="out-of-stock-label">สินค้าใกล้หมด</div>
                        ` : ''}
                        </div>
                        <div class="card-content">
                            <h3 class="name-grid">${highlightText(item.product_name, searchText)}</h3>
                            <div class="barcode">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg>
                                <span>${highlightText(item.barcode, searchText)}</span>
                            </div>
                            <p><span class="label">ราคา:</span> ${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} บาท</p>
                            <p><span class="label">ต้นทุน:</span> ${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} บาท</p>
                            <p><span class="label">สต็อก:</span> ${item.stock} ชิ้น</p>
                            <p><span class="label">เกณฑ์สั่งซื้อ:</span> ${item.reorder_level} ชิ้น</p>
                            <div class="card-actions">
                                <button onclick="openEditPanel(${item.id})"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</button>
                                <a onclick="deleteProduct(${item.id}, 'dried'); return false;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                            </div>
                        </div>
                    </div>
                `;
                    });
                }
            }

            renderPagination();
        }

        function renderPagination() {
            pagination.innerHTML = '';

            const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
            if (totalPages <= 1) return;

            if (currentPage > 1) {
                pagination.innerHTML += `<button class="page-btn" onclick="goToPage(${currentPage - 1})">ก่อนหน้า</button>`;
            }

            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);

            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            for (let i = startPage; i <= endPage; i++) {
                pagination.innerHTML += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
            }

            if (currentPage < totalPages) {
                pagination.innerHTML += `<button class="page-btn" onclick="goToPage(${currentPage + 1})">ถัดไป</button>`;
            }
        }

        function goToPage(page) {
            currentPage = page;
            displayProducts();
        }

        function filterProducts() {
            const searchText = document.getElementById('searchInput').value.trim().toLowerCase();

            if (!searchText) {
                filteredProducts = [...products];
            } else {
                filteredProducts = products.filter(item => {
                    const name = (item.product_name || '').toLowerCase();
                    const barcode = (item.barcode || '').toLowerCase();

                    const nameMatch = name.includes(searchText);
                    const barcodeMatch = barcode.includes(searchText); // ค้นหาง่ายขึ้น: ไม่จำกัดแค่ท้าย

                    return nameMatch || barcodeMatch;
                });

                // จัดลำดับ: barcode ที่ endsWith จะมาก่อน
                filteredProducts.sort((a, b) => {
                    const aEnds = a.barcode.toLowerCase().endsWith(searchText);
                    const bEnds = b.barcode.toLowerCase().endsWith(searchText);
                    return (bEnds - aEnds); // true = 1, false = 0
                });
            }

            currentPage = 1;
            displayProducts();
        }

        function switchView(viewType) {
            currentView = viewType; // บันทึกมุมมองที่เลือก
            localStorage.setItem('viewMode', viewType);
            container.scrollIntoView({
                behavior: 'smooth'
            })
            displayProducts();
        }

        // โหลดครั้งแรก
        displayProducts();

        window.onscroll = function() {
            toggleScrollButton();
        };

        // ฟังก์ชันโชว์/ซ่อนปุ่ม
        function toggleScrollButton() {
            const btn = document.getElementById("scrollTopBtn");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        }

        // ฟังก์ชันเลื่อนขึ้นบนสุด
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // เรียกฟังก์ชันเมื่อ scroll
        window.addEventListener("scroll", toggleScrollButton);

        // ตรวจสอบสถานะตอนโหลดหน้า (เผื่อโหลดในตำแหน่งเลื่อน)
        document.addEventListener("DOMContentLoaded", toggleScrollButton);


        // ฟังก์ชันสำหรับการค้นหาสินค้า Soft Drink
        const productsSoftDrink = <?php echo json_encode($soft_drink); ?>;
        const tableBodySoftDrink = document.getElementById('productTableBodySoftDrink');
        const gridSoftDrink = document.getElementById('productGridSoftDrink');
        const paginationSoftDrink = document.getElementById('paginationSoftDrink');
        const containerSoftDrink = document.getElementById('containerSoftDrink');
        const searchInputSoftDrink = document.getElementById('searchInputSoftDrink');

        let filteredProductsSoftDrink = [...productsSoftDrink];
        let currentPageSoftDrink = 1;
        const itemsPerPageSoftDrink = 10;
        let searchTextSoftDrink = '';
        let currentViewSoftDrink = localStorage.getItem('viewModeSoftDrink') || 'table';

        function highlightTextSoftDrink(text, keyword) {
            if (!keyword) return text;
            const pattern = new RegExp(`(${keyword})`, 'gi');
            return text.replace(pattern, '<span class="highlight">$1</span>');
        }

        function displayProductsSoftDrink() {
            tableBodySoftDrink.innerHTML = '';
            gridSoftDrink.innerHTML = '';

            const startIndex = (currentPageSoftDrink - 1) * itemsPerPageSoftDrink;
            const endIndex = startIndex + itemsPerPageSoftDrink;
            const paginatedItems = filteredProductsSoftDrink.slice(startIndex, endIndex);

            if (currentViewSoftDrink === 'table') {
                document.getElementById('productTableSoftDrink').style.display = 'table';
                gridSoftDrink.style.display = 'none';

                if (paginatedItems.length === 0) {
                    tableBodySoftDrink.innerHTML = `
            <tr><td colspan="8" class="no-items-message">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
                ไม่พบสินค้าที่ค้นหา
            </td></tr>`;
                } else {
                    paginatedItems.forEach(item => {
                        tableBodySoftDrink.innerHTML += `
                <tr>
                    <td style="text-align:left; font-weight: bold;">${highlightTextSoftDrink(item.product_name, searchTextSoftDrink)}</td>
                    <td>
                        <div class="product-image-container">
                            <img src="${item.image_url}" alt="${item.product_name}" width="100">
                            ${item.stock <= item.reorder_level ? `<div class="out-of-stock-label-table">สินค้าใกล้หมด</div>` : ''}
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <div class="barcode-cell">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg>
                            <span>${highlightTextSoftDrink(item.barcode, searchTextSoftDrink)}</span>
                        </div>
                    </td>
                    <td>${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</td>
                    <td>${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</td>
                    <td>${item.stock} ชิ้น</td>
                    <td>${item.reorder_level} ชิ้น</td>
                    <td>
                        <div class="action-dropdown">
                            <button onclick="toggleDropdownSoftDrink(this)">⋮</button>
                            <div class="dropdown-content">
                                <a onclick="openEditPanelSoftDrink(${item.id}); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</a>
                                <a onclick="deleteProduct(${item.id}, 'soft'); return false;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                            </div>
                        </div>
                    </td>
                </tr>`;
                    });
                }
            } else {
                document.getElementById('productTableSoftDrink').style.display = 'none';
                gridSoftDrink.style.display = 'grid';

                if (paginatedItems.length === 0) {
                    gridSoftDrink.innerHTML = `<p style="grid-column: 1 / -1; text-align: center; display: flex; align-items: center; justify-content: center; gap: 2px; ">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333" style="margin-right: 10px;">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
            ไม่พบสินค้าที่ค้นหา
        </p>`;
                } else {
                    paginatedItems.forEach(item => {
                        gridSoftDrink.innerHTML += `
                <div class="card">
                    <div class="product-image-container">
                        <img class="product-image" src="${item.image_url}" alt="${item.product_name}">
                        ${item.stock <= item.reorder_level ? `<div class="out-of-stock-label">สินค้าใกล้หมด</div>` : ''}
                    </div>
                    <div class="card-content">
                        <h3 class="name-grid">${highlightTextSoftDrink(item.product_name, searchTextSoftDrink)}</h3>
                        <div class="barcode"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg><span>${highlightTextSoftDrink(item.barcode, searchTextSoftDrink)}</span></div>
                        <p><span class="label">ราคา:</span> ${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</p>
                        <p><span class="label">ต้นทุน:</span> ${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</p>
                        <p><span class="label">สต็อก:</span> ${item.stock} ชิ้น</p>
                        <p><span class="label">เกณฑ์สั่งซื้อ:</span> ${item.reorder_level} ชิ้น</p>
                        <div class="card-actions">
                            <button onclick="openEditPanelSoftDrink(${item.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</button>
                            <a onclick="deleteProduct(${item.id}, 'soft'); return false;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                        </div>
                    </div>
                </div>`;
                    });
                }
            }

            renderPaginationSoftDrink();
        }

        function renderPaginationSoftDrink() {
            paginationSoftDrink.innerHTML = '';

            const totalPages = Math.ceil(filteredProductsSoftDrink.length / itemsPerPageSoftDrink);
            if (totalPages <= 1) return;

            if (currentPageSoftDrink > 1) {
                paginationSoftDrink.innerHTML += `<button class="page-btn" onclick="goToPageSoftDrink(${currentPageSoftDrink - 1})">ก่อนหน้า</button>`;
            }

            let startPage = Math.max(1, currentPageSoftDrink - 2);
            let endPage = Math.min(totalPages, startPage + 4);

            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            for (let i = startPage; i <= endPage; i++) {
                paginationSoftDrink.innerHTML += `<button class="page-btn ${i === currentPageSoftDrink ? 'active' : ''}" onclick="goToPageSoftDrink(${i})">${i}</button>`;
            }

            if (currentPageSoftDrink < totalPages) {
                paginationSoftDrink.innerHTML += `<button class="page-btn" onclick="goToPageSoftDrink(${currentPageSoftDrink + 1})">ถัดไป</button>`;
            }
        }

        function goToPageSoftDrink(page) {
            currentPageSoftDrink = page;
            displayProductsSoftDrink();
        }

        function filterProductsSoftDrink() {
            searchTextSoftDrink = searchInputSoftDrink.value.trim().toLowerCase();

            if (!searchTextSoftDrink) {
                filteredProductsSoftDrink = [...productsSoftDrink];
            } else {
                filteredProductsSoftDrink = productsSoftDrink.filter(item => {
                    const name = (item.product_name || '').toLowerCase();
                    const barcode = (item.barcode || '').toLowerCase();

                    return name.includes(searchTextSoftDrink) || barcode.includes(searchTextSoftDrink);
                });

                // เรียงลำดับให้ barcode ที่ลงท้ายด้วย searchText แสดงก่อน
                filteredProductsSoftDrink.sort((a, b) => {
                    const aEnds = a.barcode.toLowerCase().endsWith(searchTextSoftDrink) ? 1 : 0;
                    const bEnds = b.barcode.toLowerCase().endsWith(searchTextSoftDrink) ? 1 : 0;
                    return bEnds - aEnds;
                });
            }

            currentPageSoftDrink = 1;
            displayProductsSoftDrink();
        }

        function switchViewSoftDrink(viewType) {
            currentViewSoftDrink = viewType;
            localStorage.setItem('viewModeSoftDrink', viewType);
            containerSoftDrink.scrollIntoView({
                behavior: 'smooth'
            });
            displayProductsSoftDrink();
        }

        displayProductsSoftDrink();


        window.onscroll = function() {
            toggleScrollButtonSoftDrink();
        };

        // ฟังก์ชันโชว์/ซ่อนปุ่ม
        function toggleScrollButtonSoftDrink() {
            const btn = document.getElementById("scrollTopBtnSoftDrink");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        }

        // ฟังก์ชันเลื่อนขึ้นบนสุด
        function scrollToTopSoftDrink() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        window.addEventListener("scroll", toggleScrollButtonSoftDrink);

        document.addEventListener("DOMContentLoaded", toggleScrollButtonSoftDrink);


        // ฟังก์ชันสำหรับการค้นหาสินค้า Fresh Food
        const productsFreshFood = <?php echo json_encode($fresh_food); ?>;
        const tableBodyFreshFood = document.getElementById('productTableBodyFreshFood');
        const gridFreshFood = document.getElementById('productGridFreshFood');
        const paginationFreshFood = document.getElementById('paginationFreshFood');
        const containerFreshFood = document.getElementById('containerFreshFood');
        const searchInputFreshFood = document.getElementById('searchInputFreshFood');
        let filteredProductsFreshFood = [...productsFreshFood];
        let currentPageFreshFood = 1;
        const itemsPerPageFreshFood = 10;
        let searchTextFreshFood = '';
        let currentViewFreshFood = localStorage.getItem('viewModeFreshFood') || 'table';

        function highlightTextFreshFood(text, keyword) {
            if (!keyword) return text;
            const pattern = new RegExp(`(${keyword})`, 'gi');
            return text.replace(pattern, '<span class="highlight">$1</span>');
        }

        function displayProductsFreshFood() {
            tableBodyFreshFood.innerHTML = '';
            gridFreshFood.innerHTML = '';

            const startIndex = (currentPageFreshFood - 1) * itemsPerPageFreshFood;
            const endIndex = startIndex + itemsPerPageFreshFood;
            const paginatedItems = filteredProductsFreshFood.slice(startIndex, endIndex);

            if (currentViewFreshFood === 'table') {
                document.getElementById('productTableFreshFood').style.display = 'table';
                gridFreshFood.style.display = 'none';

                if (paginatedItems.length === 0) {
                    tableBodyFreshFood.innerHTML = `
            <tr><td colspan="8" class="no-items-message">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
                ไม่พบสินค้าที่ค้นหา
            </td></tr>`;
                } else {
                    paginatedItems.forEach(item => {
                        tableBodyFreshFood.innerHTML += `
                <tr>
                    <td style="text-align:left; font-weight: bold;">${highlightTextFreshFood(item.product_name, searchTextFreshFood)}</td>
                    <td>
                        <div class="product-image-container">
                            <img src="${item.image_url}" alt="${item.product_name}" width="100">
                            ${item.stock <= item.reorder_level ? `<div class="out-of-stock-label-table">สินค้าใกล้หมด</div>` : ''}
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <div class="barcode-cell">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg>
                            <span>${highlightTextFreshFood(item.barcode, searchTextFreshFood)}</span>
                        </div>
                    </td>
                    <td>${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</td>
                    <td>${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</td>
                    <td>${item.stock} ชิ้น</td>
                    <td>${item.reorder_level} ชิ้น</td>
                    <td>
                        <div class="action-dropdown">
                            <button onclick="toggleDropdownFreshFood(this)">⋮</button>
                            <div class="dropdown-content">
                                <a onclick="openEditPanelFreshFood(${item.id}); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</a>
                                <a onclick="deleteProduct(${item.id}, 'fresh'); return false;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                            </div>  
                        </div>
                    </td>
                </tr>`;
                    });
                }
            } else {
                document.getElementById('productTableFreshFood').style.display = 'none';
                gridFreshFood.style.display = 'grid';

                if (paginatedItems.length === 0) {
                    gridFreshFood.innerHTML = `<p style="grid-column: 1 / -1; text-align: center; display: flex; align-items: center; justify-content: center; gap: 2px; ">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333" style="margin-right: 10px;">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
            ไม่พบสินค้าที่ค้นหา
        </p>`;
                } else {
                    paginatedItems.forEach(item => {
                        gridFreshFood.innerHTML += `
                <div class="card">
                    <div class="product-image-container">
                        <img class="product-image" src="${item.image_url}" alt="${item.product_name}">
                        ${item.stock <= item.reorder_level ? `<div class="out-of-stock-label">สินค้าใกล้หมด</div>` : ''}
                    </div>
                    <div class="card-content">
                        <h3 class="name-grid">${highlightTextFreshFood(item.product_name, searchTextFreshFood)}</h3>
                        <div class="barcode">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg><span>${highlightTextFreshFood(item.barcode, searchTextFreshFood)}</span></div>
                        <p><span class="label">ราคา:</span> ${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</p>
                        <p><span class="label">ต้นทุน:</span> ${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</p>
                        <p><span class="label">สต็อก:</span> ${item.stock} ชิ้น</p>
                        <p><span class="label">เกณฑ์สั่งซื้อ:</span> ${item.reorder_level} ชิ้น</p>
                        <div class="card-actions">
                            <button onclick="openEditPanelFreshFood(${item.id})"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</button>
                            <a onclick="deleteProduct(${item.id}, 'fresh'); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                        </div>
                    </div>
                </div>`;
                    });
                }
            }
            renderPaginationFreshFood();
        }

        function renderPaginationFreshFood() {
            paginationFreshFood.innerHTML = '';

            const totalPages = Math.ceil(filteredProductsFreshFood.length / itemsPerPageFreshFood);
            if (totalPages <= 1) return;

            if (currentPageFreshFood > 1) {
                paginationFreshFood.innerHTML += `<button class="page-btn" onclick="goToPageFreshFood(${currentPageFreshFood - 1})">ก่อนหน้า</button>`;
            }

            let startPage = Math.max(1, currentPageFreshFood - 2);
            let endPage = Math.min(totalPages, startPage + 4);

            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            for (let i = startPage; i <= endPage; i++) {
                paginationFreshFood.innerHTML += `<button class="page-btn ${i === currentPageFreshFood ? 'active' : ''}" onclick="goToPageFreshFood(${i})">${i}</button>`;
            }

            if (currentPageFreshFood < totalPages) {
                paginationFreshFood.innerHTML += `<button class="page-btn" onclick="goToPageFreshFood(${currentPageFreshFood + 1})">ถัดไป</button>`;
            }
        }

        function goToPageFreshFood(page) {
            currentPageFreshFood = page;
            displayProductsFreshFood();
        }

        function filterProductsFreshFood() {
            searchTextFreshFood = searchInputFreshFood.value.trim().toLowerCase();

            if (!searchTextFreshFood) {
                filteredProductsFreshFood = [...productsFreshFood];
            } else {
                filteredProductsFreshFood = productsFreshFood.filter(item => {
                    const name = (item.product_name || '').toLowerCase();
                    const barcode = (item.barcode || '').toLowerCase();

                    return name.includes(searchTextFreshFood) || barcode.includes(searchTextFreshFood);
                });

                filteredProductsFreshFood.sort((a, b) => {
                    const aEnds = a.barcode.toLowerCase().endsWith(searchTextFreshFood) ? 1 : 0;
                    const bEnds = b.barcode.toLowerCase().endsWith(searchTextFreshFood) ? 1 : 0;
                    return bEnds - aEnds;
                });
            }

            currentPageFreshFood = 1;
            displayProductsFreshFood();
        }

        function switchViewFreshFood(viewType) {
            currentViewFreshFood = viewType;
            localStorage.setItem('viewModeFreshFood', viewType);
            containerFreshFood.scrollIntoView({
                behavior: 'smooth'
            });
            displayProductsFreshFood();
        }
        displayProductsFreshFood();

        window.onscroll = function() {
            toggleScrollButtonFreshFood();
        };
        // ฟังก์ชันโชว์/ซ่อนปุ่ม
        function toggleScrollButtonFreshFood() {
            const btn = document.getElementById("scrollTopBtnFreshFood");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        }
        // ฟังก์ชันเลื่อนขึ้นบนสุด
        function scrollToTopFreshFood() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        window.addEventListener("scroll", toggleScrollButtonFreshFood);

        document.addEventListener("DOMContentLoaded", toggleScrollButtonFreshFood);


        // ฟังก์ชันสำหรับการค้นหาสินค้า SnackFood
        const productsSnackFood = <?php echo json_encode($snack); ?>;
        const tableBodySnackFood = document.getElementById('productTableBodySnackFood');
        const gridSnackFood = document.getElementById('productGridSnackFood');
        const paginationSnackFood = document.getElementById('paginationSnackFood');
        const containerSnackFood = document.getElementById('containerSnackFood');
        const searchInputSnackFood = document.getElementById('searchInputSnackFood');
        let filteredProductsSnackFood = [...productsSnackFood];
        let currentPageSnackFood = 1;
        const itemsPerPageSnackFood = 10;
        let searchTextSnackFood = '';
        let currentViewSnackFood = localStorage.getItem('viewModeSnackFood') || 'table';

        function highlightTextSnackFood(text, keyword) {
            if (!keyword) return text;
            const pattern = new RegExp(`(${keyword})`, 'gi');
            return text.replace(pattern, '<span class="highlight">$1</span>');
        }

        function displayProductsSnackFood() {
            tableBodySnackFood.innerHTML = '';
            gridSnackFood.innerHTML = '';

            const startIndex = (currentPageSnackFood - 1) * itemsPerPageSnackFood;
            const endIndex = startIndex + itemsPerPageSnackFood;
            const paginatedItems = filteredProductsSnackFood.slice(startIndex, endIndex);

            if (currentViewSnackFood === 'table') {
                document.getElementById('productTableSnackFood').style.display = 'table';
                gridSnackFood.style.display = 'none';

                if (paginatedItems.length === 0) {
                    tableBodySnackFood.innerHTML = `
        <tr><td colspan="8" class="no-items-message">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
            ไม่พบสินค้าที่ค้นหา
        </td></tr>`;
                } else {
                    paginatedItems.forEach(item => {
                        tableBodySnackFood.innerHTML += `
            <tr>
                <td style="text-align:left; font-weight: bold;">${highlightTextSnackFood(item.product_name, searchTextSnackFood)}</td>
                <td>
                    <div class="product-image-container">
                        <img src="${item.image_url}" alt="${item.product_name}" width="100">
                        ${item.stock <= item.reorder_level ? `<div class="out-of-stock-label-table">สินค้าใกล้หมด</div>` : ''}
                    </div>
                </td>
                <td style="text-align:center;">
                    <div class="barcode-cell">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg>
                        <span>${highlightTextSnackFood(item.barcode, searchTextSnackFood)}</span>
                    </div>
                </td>
                <td>${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</td>
                <td>${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</td>
                <td>${item.stock} ชิ้น</td>
                <td>${item.reorder_level} ชิ้น</td>
                <td>
                    <div class="action-dropdown">
                        <button onclick="toggleDropdownSnackFood(this)">⋮</button>
                        <div class="dropdown-content">
                            <a onclick="openEditPanelSnackFood(${item.id}); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</a>
                            <a onclick="deleteProduct(${item.id}, 'snack'); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                        </div>  
                    </div>
                </td>
            </tr>`;
                    });
                }
            } else {
                document.getElementById('productTableSnackFood').style.display = 'none';
                gridSnackFood.style.display = 'grid';

                if (paginatedItems.length === 0) {
                    gridSnackFood.innerHTML = `<p style="grid-column: 1 / -1; text-align: center; display: flex; align-items: center; justify-content: center; gap: 2px; ">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333" style="margin-right: 10px;">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
            ไม่พบสินค้าที่ค้นหา
        </p>`;
                } else {
                    paginatedItems.forEach(item => {
                        gridSnackFood.innerHTML += `
            <div class="card">
                <div class="product-image-container">
                    <img class="product-image" src="${item.image_url}" alt="${item.product_name}">
                    ${item.stock <= item.reorder_level ? `<div class="out-of-stock-label">สินค้าใกล้หมด</div>` : ''}
                </div>
                <div class="card-content">
                    <h3 class="name-grid">${highlightTextSnackFood(item.product_name, searchTextSnackFood)}</h3>
                    <div class="barcode">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg><span>${highlightTextSnackFood(item.barcode, searchTextSnackFood)}</span></div>
                    <p><span class="label">ราคา:</span> ${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</p>
                    <p><span class="label">ต้นทุน:</span> ${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</p>
                    <p><span class="label">สต็อก:</span> ${item.stock} ชิ้น</p>
                    <p><span class="label">เกณฑ์สั่งซื้อ:</span> ${item.reorder_level} ชิ้น</p>
                    <div class="card-actions">
                        <button onclick="openEditPanelSnackFood(${item.id})"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</button>
                        <a onclick="deleteProduct(${item.id}, 'snack'); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                    </div>
                </div>
            </div>`;
                    });
                }
            }
            renderPaginationSnackFood();
        }

        function renderPaginationSnackFood() {
            paginationSnackFood.innerHTML = '';

            const totalPages = Math.ceil(filteredProductsSnackFood.length / itemsPerPageSnackFood);
            if (totalPages <= 1) return;

            if (currentPageSnackFood > 1) {
                paginationSnackFood.innerHTML += `<button class="page-btn" onclick="goToPageSnackFood(${currentPageSnackFood - 1})">ก่อนหน้า</button>`;
            }

            let startPage = Math.max(1, currentPageSnackFood - 2);
            let endPage = Math.min(totalPages, startPage + 4);

            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            for (let i = startPage; i <= endPage; i++) {
                paginationSnackFood.innerHTML += `<button class="page-btn ${i === currentPageSnackFood ? 'active' : ''}" onclick="goToPageSnackFood(${i})">${i}</button>`;
            }

            if (currentPageSnackFood < totalPages) {
                paginationSnackFood.innerHTML += `<button class="page-btn" onclick="goToPageSnackFood(${currentPageSnackFood + 1})">ถัดไป</button>`;
            }
        }

        function goToPageSnackFood(page) {
            currentPageSnackFood = page;
            displayProductsSnackFood();
        }

        function filterProductsSnackFood() {
            searchTextSnackFood = searchInputSnackFood.value.trim().toLowerCase();

            if (!searchTextSnackFood) {
                filteredProductsSnackFood = [...productsSnackFood];
            } else {
                filteredProductsSnackFood = productsSnackFood.filter(item => {
                    const name = (item.product_name || '').toLowerCase();
                    const barcode = (item.barcode || '').toLowerCase();

                    return name.includes(searchTextSnackFood) || barcode.includes(searchTextSnackFood);
                });

                filteredProductsSnackFood.sort((a, b) => {
                    const aEnds = a.barcode.toLowerCase().endsWith(searchTextSnackFood) ? 1 : 0;
                    const bEnds = b.barcode.toLowerCase().endsWith(searchTextSnackFood) ? 1 : 0;
                    return bEnds - aEnds;
                });
            }

            currentPageSnackFood = 1;
            displayProductsSnackFood();
        }

        function switchViewSnackFood(viewType) {
            currentViewSnackFood = viewType;
            localStorage.setItem('viewModeSnackFood', viewType);
            containerSnackFood.scrollIntoView({
                behavior: 'smooth'
            });
            displayProductsSnackFood();
        }

        displayProductsSnackFood();

        window.onscroll = function() {
            toggleScrollButtonSnackFood();
        };

        // ฟังก์ชันโชว์/ซ่อนปุ่ม
        function toggleScrollButtonSnackFood() {
            const btn = document.getElementById("scrollTopBtnSnackFood");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        }

        // ฟังก์ชันเลื่อนขึ้นบนสุด
        function scrollToTopSnackFood() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        window.addEventListener("scroll", toggleScrollButtonSnackFood);

        document.addEventListener("DOMContentLoaded", toggleScrollButtonSnackFood);


        const productsStationery = <?php echo json_encode($stationery); ?>;
        const tableBodyStationery = document.getElementById('productTableBodyStationery');
        const gridStationery = document.getElementById('productGridStationery');
        const paginationStationery = document.getElementById('paginationStationery');
        const containerStationery = document.getElementById('containerStationery');
        const searchInputStationery = document.getElementById('searchInputStationery');
        let filteredProductsStationery = [...productsStationery];
        let currentPageStationery = 1;
        const itemsPerPageStationery = 10;
        let searchTextStationery = '';
        let currentViewStationery = localStorage.getItem('viewModeStationery') || 'table';

        function highlightTextStationery(text, keyword) {
            if (!keyword) return text;
            const pattern = new RegExp(`(${keyword})`, 'gi');
            return text.replace(pattern, '<span class="highlight">$1</span>');
        }

        function displayProductsStationery() {
            tableBodyStationery.innerHTML = '';
            gridStationery.innerHTML = '';

            const startIndex = (currentPageStationery - 1) * itemsPerPageStationery;
            const endIndex = startIndex + itemsPerPageStationery;
            const paginatedItems = filteredProductsStationery.slice(startIndex, endIndex);

            if (currentViewStationery === 'table') {
                document.getElementById('productTableStationery').style.display = 'table';
                gridStationery.style.display = 'none';

                if (paginatedItems.length === 0) {
                    tableBodyStationery.innerHTML = `
        <tr><td colspan="8" class="no-items-message">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
            ไม่พบสินค้าที่ค้นหา
        </td></tr>`;
                } else {
                    paginatedItems.forEach(item => {
                        tableBodyStationery.innerHTML += `
            <tr>
                <td style="text-align:left; font-weight: bold;">${highlightTextStationery(item.product_name, searchTextStationery)}</td>
                <td>
                    <div class="product-image-container">
                        <img src="${item.image_url}" alt="${item.product_name}" width="100">
                        ${item.stock <= item.reorder_level ? `<div class="out-of-stock-label-table">สินค้าใกล้หมด</div>` : ''}
                    </div>
                </td>
                <td style="text-align:center;">
                    <div class="barcode-cell">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg>
                        <span>${highlightTextStationery(item.barcode, searchTextStationery)}</span>
                    </div>
                </td>
                <td>${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</td>
                <td>${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</td>
                <td>${item.stock} ชิ้น</td>
                <td>${item.reorder_level} ชิ้น</td>
                <td>
                    <div class="action-dropdown">
                        <button onclick="toggleDropdownStationery(this)">⋮</button>
                        <div class="dropdown-content">
                            <a onclick="openEditPanelStationery(${item.id}); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</a>
                            <a onclick="deleteProduct(${item.id}, 'stationery'); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                        </div>  
                    </div>
                </td>
            </tr>`;
                    });
                }
            } else {
                document.getElementById('productTableStationery').style.display = 'none';
                gridStationery.style.display = 'grid';

                if (paginatedItems.length === 0) {
                    gridStationery.innerHTML = `<p style="grid-column: 1 / -1; text-align: center; display: flex; align-items: center; justify-content: center; gap: 2px; ">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333" style="margin-right: 10px;">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
            ไม่พบสินค้าที่ค้นหา
        </p>`;
                } else {
                    paginatedItems.forEach(item => {
                        gridStationery.innerHTML += `
            <div class="card">
                <div class="product-image-container">
                    <img class="product-image" src="${item.image_url}" alt="${item.product_name}">
                    ${item.stock <= item.reorder_level ? `<div class="out-of-stock-label">สินค้าใกล้หมด</div>` : ''}
                </div>
                <div class="card-content">
                    <h3 class="name-grid">${highlightTextStationery(item.product_name, searchTextStationery)}</h3>
                    <div class="barcode">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg><span>${highlightTextStationery(item.barcode, searchTextStationery)}</span></div>
                    <p><span class="label">ราคา:</span> ${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</p>
                    <p><span class="label">ต้นทุน:</span> ${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</p>
                    <p><span class="label">สต็อก:</span> ${item.stock} ชิ้น</p>
                    <p><span class="label">เกณฑ์สั่งซื้อ:</span> ${item.reorder_level} ชิ้น</p>
                    <div class="card-actions">
                        <button onclick="openEditPanelStationery(${item.id})"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</button>
                        <a onclick="deleteProduct(${item.id}, 'stationery'); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                    </div>
                </div>
            </div>`;
                    });
                }
            }
            renderPaginationStationery();
        }

        function renderPaginationStationery() {
            paginationStationery.innerHTML = '';

            const totalPages = Math.ceil(filteredProductsStationery.length / itemsPerPageStationery);
            if (totalPages <= 1) return;

            if (currentPageStationery > 1) {
                paginationStationery.innerHTML += `<button class="page-btn" onclick="goToPageStationery(${currentPageStationery - 1})">ก่อนหน้า</button>`;
            }

            let startPage = Math.max(1, currentPageStationery - 2);
            let endPage = Math.min(totalPages, startPage + 4);

            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            for (let i = startPage; i <= endPage; i++) {
                paginationStationery.innerHTML += `<button class="page-btn ${i === currentPageStationery ? 'active' : ''}" onclick="goToPageStationery(${i})">${i}</button>`;
            }

            if (currentPageStationery < totalPages) {
                paginationStationery.innerHTML += `<button class="page-btn" onclick="goToPageStationery(${currentPageStationery + 1})">ถัดไป</button>`;
            }
        }

        function goToPageStationery(page) {
            currentPageStationery = page;
            displayProductsStationery();
        }

        function filterProductsStationery() {
            searchTextStationery = searchInputStationery.value.trim().toLowerCase();

            if (!searchTextStationery) {
                filteredProductsStationery = [...productsStationery];
            } else {
                filteredProductsStationery = productsStationery.filter(item => {
                    const name = (item.product_name || '').toLowerCase();
                    const barcode = (item.barcode || '').toLowerCase();

                    return name.includes(searchTextStationery) || barcode.includes(searchTextStationery);
                });

                filteredProductsStationery.sort((a, b) => {
                    const aEnds = a.barcode.toLowerCase().endsWith(searchTextStationery) ? 1 : 0;
                    const bEnds = b.barcode.toLowerCase().endsWith(searchTextStationery) ? 1 : 0;
                    return bEnds - aEnds;
                });
            }

            currentPageStationery = 1;
            displayProductsStationery();
        }

        function switchViewStationery(viewType) {
            currentViewStationery = viewType;
            localStorage.setItem('viewModeStationery', viewType);
            containerStationery.scrollIntoView({
                behavior: 'smooth'
            });
            displayProductsStationery();
        }

        displayProductsStationery();

        window.onscroll = function() {
            toggleScrollButtonStationery();
        };

        // ฟังก์ชันโชว์/ซ่อนปุ่ม
        function toggleScrollButtonStationery() {
            const btn = document.getElementById("scrollTopBtnStationery");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        }

        // ฟังก์ชันเลื่อนขึ้นบนสุด
        function scrollToTopStationery() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        window.addEventListener("scroll", toggleScrollButtonStationery);

        document.addEventListener("DOMContentLoaded", toggleScrollButtonStationery);


        // ฟังก์ชันสำหรับการเลือกหมวดหมู่สินค้า
        let selectedCategory = '';

        function selectCategory(event, category) {
            selectedCategory = category;
            showAlertToast(`เลือกหมวดหมู่สินค้า : ${category}`, icons.success, 'success');
            console.log("หมวดหมู่ที่เลือก:", selectedCategory);

            // ล้าง class 'active' ออกจากปุ่มทั้งหมด
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // เพิ่ม class 'active' ให้กับปุ่มที่ถูกเลือก
            event.currentTarget.classList.add('active');
        }

        function handleFormSubmit(event) {
            event.preventDefault(); // ป้องกันการรีเฟรชหน้า

            if (!selectedCategory) {
                showAlertToast("กรุณาเลือกหมวดหมู่สินค้าก่อน!", icons.error, 'error');
                return;
            }

            const productName = document.getElementById('productName').value;
            const productImage = document.getElementById('productImage').value;
            const barcode = document.getElementById('barcode').value;
            const productPrice = document.getElementById('productPrice').value;
            const productCost = document.getElementById('productCost').value;
            const productStock = document.getElementById('productStock').value;
            const productReorderLevel = document.getElementById('productReorderLevel').value;

            if (productName && productImage && barcode && productPrice && productCost && productStock && productReorderLevel) {
                sendDataToTable(
                    selectedCategory,
                    productName,
                    productImage,
                    barcode,
                    productPrice,
                    productCost,
                    productStock,
                    productReorderLevel
                );

                document.getElementById('uploadForm').reset(); // รีเซ็ตฟอร์ม

                showAlertToast("อัปโหลดสินค้าสำเร็จ!", icons.success, 'success'); // ✅ แสดง Toast

                setTimeout(() => {
                    location.reload(); // ✅ รีเฟรชหลัง 1 วิ
                }, 1000);
            } else {
                showAlertToast("กรุณากรอกข้อมูลให้ครบถ้วน!", icons.error, 'error');
            }
        }


        function sendDataToTable(category, productName, productImage, barcode, productPrice, productCost, productStock, productReorderLevel) {
            const tableBody = document.getElementById(`${category}_table`);

            if (!tableBody) {
                showAlertToast("ไม่พบตารางสำหรับหมวดหมู่นี้!", icons.error, 'error');
                return;
            }

            // สร้างแถวใหม่ในตาราง
            const row = document.createElement('tr');
            row.innerHTML = `
            <td><input type="checkbox" class="row-checkbox" data-product="${productName}" onchange="updateCheckboxStatus(event, '${category}')"></td>
            <td>${productName}</td>
            <td><img src="${productImage}" alt="${productName}" style="width: 100px; height: auto;"></td>
            <td>${barcode}</td>
            <td>${productPrice} บาท</td>
            <td>${productCost} บาท</td>
            <td>${productStock} ชิ้น</td>
            <td>${productReorderLevel} ชิ้น</td>
            `;
            tableBody.appendChild(row);

            // บันทึกข้อมูลสินค้าและสถานะ checkbox ลงใน localStorage
            saveToLocalStorage(category, productName, productImage, barcode, productPrice, productCost, productStock, productReorderLevel);

            // เรียกใช้ฟังก์ชัน loadFromLocalStorage เพื่อรีเฟรชข้อมูลทันที
            loadFromLocalStorage();

            // รีเซ็ตฟอร์ม
            document.getElementById('uploadForm').reset();
        }


        function saveToLocalStorage(category, productName, productImage, barcode, productPrice, productCost, productStock, productReorderLevel) {
            let savedData = JSON.parse(localStorage.getItem(category)) || [];

            const productData = {
                productName,
                productImage,
                barcode,
                productPrice,
                productCost,
                productStock,
                productReorderLevel,
                isChecked: false // สถานะของ checkbox
            };

            savedData.push(productData);
            localStorage.setItem(category, JSON.stringify(savedData));
        }

        function loadFromLocalStorage() {
            const categories = ['dried_food', 'soft_drink', 'fresh_food', 'snack', 'stationery'];
            categories.forEach(category => {
                const savedData = JSON.parse(localStorage.getItem(category)) || [];
                const tableBody = document.getElementById(`${category}_table`);

                // ล้างข้อมูลเดิมก่อน
                tableBody.innerHTML = '';

                if (savedData.length === 0) {
                    // ถ้าไม่มีข้อมูลให้แสดงข้อความ
                    const noDataRow = document.createElement('tr');
                    noDataRow.innerHTML = `<td colspan="8" style="text-align: center; color: #000000;">ไม่มีสินค้าที่สามารถบันทึกได้</td>`;
                    tableBody.appendChild(noDataRow);
                } else {
                    // ถ้ามีข้อมูลให้เพิ่มแถวของสินค้า
                    savedData.forEach(data => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                    <td><input type="checkbox" class="row-checkbox" data-product="${data.productName}" ${data.isChecked ? 'checked' : ''} onchange="updateCheckboxStatus(event, '${category}')"></td>
                    <td>${data.productName}</td>
                    <td><img src="${data.productImage}" alt="${data.productName}" style="width: 100px; height: auto;"></td>
                    <td>${data.barcode}</td>
                    <td>${data.productPrice} บาท</td>
                    <td>${data.productCost} บาท</td>
                    <td>${data.productStock} ชิ้น</td>
                    <td>${data.productReorderLevel} ชิ้น</td>
                `;
                        tableBody.appendChild(row);
                    });
                }
            });
        }

        // ฟังก์ชันที่ใช้ในการอัปเดตสถานะของ checkbox ใน localStorage
        function updateCheckboxStatus(event, category) {
            const checkbox = event.target;
            const productName = checkbox.getAttribute('data-product');

            // ดึงข้อมูลจาก localStorage
            const savedData = JSON.parse(localStorage.getItem(category)) || [];

            // อัปเดตสถานะ checkbox ที่เลือก
            const updatedData = savedData.map(data => {
                if (data.productName === productName) {
                    data.isChecked = checkbox.checked; // ถ้าเลือก จะเป็น true, ถ้าไม่เลือก จะเป็น false
                }
                return data;
            });

            // บันทึกข้อมูลกลับลงใน localStorage
            localStorage.setItem(category, JSON.stringify(updatedData));
        }

        // เรียกใช้เมื่อโหลดหน้า
        window.onload = function() {
            loadFromLocalStorage();
        };

        // ฟังก์ชันเลือกสินค้าทั้งหมด tableId
        function toggleSelectAllForTable(source, tableId) {
            const table = document.getElementById(tableId);
            if (!table) return;

            table.querySelectorAll('.row-checkbox').forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }

        function sendProductsToServer(selectedProducts, url, storageKey) {
            console.log("ส่งข้อมูลสินค้า:", selectedProducts);

            fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        products: selectedProducts
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlertToast("เพิ่มสินค้าสำเร็จ!", icons.success, "success-toast");

                        // ✅ ลบข้อมูลใน localStorage หลังเพิ่มสำเร็จ
                        localStorage.removeItem(storageKey);

                        setTimeout(() => location.reload(), 1000);
                        document.querySelectorAll(".row-checkbox:checked").forEach(cb => cb.closest("tr").remove());
                    } else {
                        showAlertToast("เกิดข้อผิดพลาด: " + data.message, icons.error, "alert-toast");
                    }
                })
                .catch(err => {
                    showAlertToast("เกิดข้อผิดพลาดในการส่งข้อมูล", icons.error, "alert-toast");
                    console.error(err);
                });
        }

        function setupProductButton(className, storageKey, postUrl) {
            document.querySelectorAll(className).forEach(button => {
                button.addEventListener("click", () => {
                    let storedData = JSON.parse(localStorage.getItem(storageKey) || "[]");

                    if (!Array.isArray(storedData) || storedData.length === 0) {
                        showAlertToast("ไม่มีข้อมูลที่จะเพิ่มสินค้า", icons.error, "alert-toast");
                        return;
                    }

                    let selectedProducts = [];
                    document.querySelectorAll(".row-checkbox:checked").forEach(checkbox => {
                        let row = checkbox.closest("tr");
                        selectedProducts.push({
                            productName: row.children[1].textContent.trim(),
                            productImage: row.children[2].querySelector("img")?.src || "",
                            barcode: row.children[3].textContent.trim(),
                            productPrice: parseFloat(row.children[4].textContent.replace(" บาท", "").trim()),
                            productCost: parseFloat(row.children[5].textContent.replace(" บาท", "").trim()),
                            productStock: parseInt(row.children[6].textContent.replace(" ชิ้น", "").trim()),
                            productReorderLevel: parseInt(row.children[7].textContent.replace(" ชิ้น", "").trim()),
                        });
                    });

                    if (selectedProducts.length === 0) {
                        showAlertToast("กรุณาเลือกสินค้าอย่างน้อยหนึ่งรายการ", icons.select, "alert-toast");
                        return;
                    }

                    const modal = document.getElementById("addProductConfirmModal");
                    modal.classList.remove("hidden");

                    const confirmBtn = document.getElementById("confirmAddProductBtn");
                    const cancelBtn = document.getElementById("cancelAddProductBtn");

                    confirmBtn.replaceWith(confirmBtn.cloneNode(true));
                    cancelBtn.replaceWith(cancelBtn.cloneNode(true));

                    const newConfirmBtn = document.getElementById("confirmAddProductBtn");
                    const newCancelBtn = document.getElementById("cancelAddProductBtn");

                    newConfirmBtn.addEventListener("click", () => {
                        modal.classList.add("hidden");
                        sendProductsToServer(selectedProducts, postUrl, storageKey); // ✅ ส่ง storageKey ไปด้วย
                    });

                    newCancelBtn.addEventListener("click", () => {
                        modal.classList.add("hidden");
                    });
                });
            });
        }

        // เรียกใช้งานกับประเภทต่าง ๆ
        setupProductButton(".btn-dried-food", "dried_food", "/sci-next/product/upload_product/add_food_all/add_dried_food.php");
        setupProductButton(".btn-soft-drink", "soft_drink", "/sci-next/product/upload_product/add_food_all/add_soft_drink.php");
        setupProductButton(".btn-fresh-food", "fresh_food", "/sci-next/product/upload_product/add_food_all/add_fresh_food.php");
        setupProductButton(".btn-snack", "snack", "/sci-next/product/upload_product/add_food_all/add_snack.php");
        setupProductButton(".btn-stationery", "stationery", "/sci-next/product/upload_product/add_food_all/add_stationery.php");

        // ปิด modal เมื่อคลิกข้างนอก
        window.addEventListener("click", function(event) {
            const modal = document.getElementById("addProductConfirmModal");
            if (event.target === modal) {
                modal.classList.add("hidden");
            }
        });


        // ฟังก์ชันสำหรับเปิด Modal แก้ไขสินค้า localStorage
        let editingBarcode = null;

        // เริ่มทำงานเมื่อกดปุ่ม "แก้ไขสินค้า"
        document.querySelectorAll(".btn-edit-product-localStorage").forEach(button => {
            button.addEventListener("click", function() {
                const key = this.dataset.key;
                const storedData = JSON.parse(localStorage.getItem(key)) || [];

                // ✅ ไม่มีข้อมูลใน localStorage
                if (storedData.length === 0) {
                    showAlertToast("ไม่มีข้อมูลที่จะแก้ไข", icons.error, "alert-toast");
                    return;
                }

                const checkedBoxes = document.querySelectorAll(".row-checkbox:checked");

                // ✅ ไม่ได้เลือกเลย
                if (checkedBoxes.length === 0) {
                    showAlertToast("กรุณาเลือกสินค้าที่ต้องการแก้ไข", icons.select, "alert-toast");
                    return;
                }

                // ✅ เลือกมากกว่า 1 รายการ
                if (checkedBoxes.length > 1) {
                    showAlertToast("กรุณาเลือกสินค้าเพียง 1 รายการเพื่อแก้ไข", icons.select, "alert-toast");
                    return;
                }

                const checked = checkedBoxes[0];
                const row = checked.closest("tr");

                if (!row) {
                    showAlertToast("ไม่พบแถวของสินค้าในตาราง", icons.error, "alert-toast");
                    return;
                }

                const barcode = row.children[3]?.textContent.trim();
                if (!barcode) {
                    showAlertToast("ไม่สามารถดึงข้อมูลบาร์โค้ดจากแถวที่เลือกได้", icons.error, "alert-toast");
                    return;
                }

                const product = storedData.find(item => item.barcode === barcode);
                if (!product) {
                    showAlertToast("ไม่พบสินค้านี้ใน localStorage", icons.empty, "alert-toast");
                    return;
                }

                // เติมข้อมูลลงฟอร์ม
                document.getElementById("editImagePreview").src = product.productImage || "";
                document.getElementById("editName").value = product.productName;
                document.getElementById("editBarcode").value = product.barcode;
                document.getElementById("editPrice").value = product.productPrice;
                document.getElementById("editCost").value = product.productCost;
                document.getElementById("editStock").value = product.productStock;
                document.getElementById("editReorder").value = product.productReorderLevel;

                editingBarcode = barcode;
                document.getElementById("editForm").dataset.key = key;
                document.getElementById("editModal").style.display = "flex";
            });
        });

        // ปิด Modal เมื่อกดปุ่มปิด
        document.getElementById("closeModal").addEventListener("click", () => {
            document.getElementById("editModal").style.display = "none";
            editingBarcode = null;
        });

        // ปิด Modal เมื่อคลิกนอกกล่อง
        window.addEventListener("click", function(e) {
            const modal = document.getElementById("editModal");
            if (e.target === modal) {
                modal.style.display = "none";
                editingBarcode = null;
            }
        });

        // บันทึกการแก้ไข
        document.getElementById("editForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const key = this.dataset.key;
            let storedData = JSON.parse(localStorage.getItem(key)) || [];

            const newBarcode = document.getElementById("editBarcode").value.trim();
            const updatedProduct = {
                productName: document.getElementById("editName").value.trim(),
                barcode: newBarcode,
                productImage: document.getElementById("editImagePreview").src,
                productPrice: document.getElementById("editPrice").value,
                productCost: document.getElementById("editCost").value,
                productStock: document.getElementById("editStock").value,
                productReorderLevel: document.getElementById("editReorder").value,
            };

            // อัปเดตใน localStorage
            storedData = storedData.map(item => {
                if (item.barcode === editingBarcode) {
                    return {
                        ...item,
                        ...updatedProduct
                    };
                }
                return item;
            });
            localStorage.setItem(key, JSON.stringify(storedData));

            // อัปเดตแถวในตาราง
            const checked = document.querySelector(".row-checkbox:checked");
            const row = checked?.closest("tr");

            if (!row || row.children.length < 8) {
                showAlertToast("เกิดข้อผิดพลาดในการอัปเดตแถว", icons.error, "alert-toast");
                return;
            }

            row.children[1].textContent = updatedProduct.productName;
            row.children[2].querySelector("img").src = updatedProduct.productImage;
            row.children[3].textContent = updatedProduct.barcode;
            row.children[4].textContent = `${updatedProduct.productPrice} บาท`;
            row.children[5].textContent = `${updatedProduct.productCost} บาท`;
            row.children[6].textContent = `${updatedProduct.productStock} ชิ้น`;
            row.children[7].textContent = `${updatedProduct.productReorderLevel} ชิ้น`;

            // ปิด Modal
            document.getElementById("editModal").style.display = "none";
            editingBarcode = null;

            // แสดง toast สำเร็จ
            showAlertToast(`แก้ไขสินค้าเรียบร้อยแล้ว`, icons.success, "success-toast");
            setTimeout(() => {
                location.reload();
            }, 1000); // รีเฟรชหน้าใหม่หลังจาก 1 วินาที
        });


        let deleteCallback = null;

        // เริ่มใช้งานปุ่มลบสินค้า
        document.querySelectorAll(".btn-delete-product").forEach(button => {
            button.addEventListener("click", function() {
                const key = this.dataset.key;
                const table = document.getElementById(`${key}_table`);
                const storedData = JSON.parse(localStorage.getItem(key)) || [];

                if (!table) {
                    showAlertToast("ไม่พบตารางสำหรับสินค้า", icons.error, "alert-toast");
                    return;
                }

                // ดึงบาร์โค้ดของสินค้าที่ถูกเลือก
                const checkedBarcodes = Array.from(table.querySelectorAll(".row-checkbox:checked"))
                    .map(cb => cb.closest("tr").children[3]?.textContent.trim())
                    .filter(Boolean); // กรองค่าที่เป็น null หรือ undefined

                if (storedData.length === 0) {
                    showAlertToast("ไม่มีข้อมูลที่จะลบ", icons.error, "alert-toast");
                    return;
                }

                if (checkedBarcodes.length === 0) {
                    showAlertToast("กรุณาเลือกสินค้าเพื่อทำการลบ", icons.select, "alert-toast");
                    return;
                }

                // แสดง Modal ยืนยัน
                const deleteMessage = document.getElementById("deleteMessage");
                deleteMessage.innerHTML = `คุณแน่ใจหรือไม่ว่าต้องการลบ <div class="highlight-text">สินค้า ${checkedBarcodes.length} รายการ?</div>`;

                const modal = document.getElementById("deleteConfirmModal");
                modal.style.display = "flex";

                // สร้าง callback สำหรับการยืนยันลบ
                deleteCallback = () => {
                    const updatedData = storedData.filter(item => !checkedBarcodes.includes(item.barcode));
                    localStorage.setItem(key, JSON.stringify(updatedData));

                    // ลบแถวจาก DOM
                    table.querySelectorAll(".row-checkbox:checked").forEach(cb => {
                        const row = cb.closest("tr");
                        if (row) row.remove();
                    });

                    showAlertToast(`ลบสินค้า ${checkedBarcodes.length} รายการเรียบร้อยแล้ว`, icons.success, "success-toast");
                    setTimeout(() => {
                        location.reload();
                    }, 1000); // รีเฟรชหน้าใหม่หลังจาก 1 วินาที

                    closeModal();
                };
            });
        });

        // ปุ่มยืนยันลบ
        document.getElementById("confirmDeleteBtn").addEventListener("click", () => {
            if (typeof deleteCallback === "function") {
                deleteCallback();
            }
        });

        // ปุ่มยกเลิก
        document.getElementById("cancelDeleteBtn").addEventListener("click", closeModal);

        window.addEventListener("click", function(e) {
            const modal = document.getElementById("deleteConfirmModal");
            if (e.target === modal) {
                closeModal();
            }
        });

        // ปิด Modal และล้าง callback
        function closeModal() {
            document.getElementById("deleteConfirmModal").style.display = "none";
            deleteCallback = null;
        }

        let loadingInterval; // สำหรับเก็บ interval ของ progress

        function showTab(tabId) {
            const loadingBar = document.getElementById("tabLoadingBar");
            let progress = 0;
            loadingBar.style.width = "0%";
            loadingBar.style.opacity = "1";

            // เริ่มโหลดแบบค่อยๆ เพิ่มขึ้น
            loadingInterval = setInterval(() => {
                if (progress < 90) {
                    progress += Math.random() * 5; // ค่อยๆ เพิ่มแบบสุ่ม
                    loadingBar.style.width = progress + "%";
                }
            }, 80);

            // โหลดคอนเทนต์จริง (mock delay 400ms)
            setTimeout(() => {
                clearInterval(loadingInterval); // หยุด interval

                // เปลี่ยนแท็บ
                document.querySelectorAll('.content').forEach(content => content.classList.remove('show'));
                document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));

                const activeContent = document.getElementById(tabId);
                if (activeContent) activeContent.classList.add('show');

                const activeTabButton = document.querySelector(`.tab[data-tab="${tabId}"]`);
                if (activeTabButton) activeTabButton.classList.add('active');

                localStorage.setItem("activeTab", tabId);

                // โหลดจบ → ดัน 100% แล้วจางหาย
                loadingBar.style.width = "100%";
                setTimeout(() => {
                    loadingBar.style.opacity = "0";
                }, 400);
            }, 400); // ความล่าช้าสำหรับ simulate loading จริง
        }

        // event listener
        document.querySelectorAll('.tab[data-tab]').forEach(tab => {
            tab.addEventListener('click', () => {
                const tabId = tab.getAttribute('data-tab');
                showTab(tabId);
            });
        });

        // โหลดแท็บล่าสุด
        const savedTab = localStorage.getItem("activeTab");
        showTab(savedTab);


        function displayMessage(message, type) {
            // สร้าง element สำหรับแสดงข้อความ
            var messageBox = document.createElement('div');
            messageBox.classList.add('message-box', type); // เพิ่มคลาสตามประเภทของข้อความ
            messageBox.innerHTML = message;

            // แสดงข้อความใน body
            document.body.appendChild(messageBox);

            // ซ่อนข้อความหลังจาก 3 วินาที
            setTimeout(function() {
                messageBox.remove();
            }, 500);
        }

        // ฟังก์ชั่นที่ทำงานเมื่อฟอร์มถูกส่ง (สำหรับการสร้าง employee)
        function submitemployeeForm() {
            var form = document.getElementById('employeeSignupForm');
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var isValid = true;

            // ล้างเส้นขอบผิดพลาดจากฟอร์ม
            clearInputErrors();

            // ตรวจสอบรหัสผ่านและยืนยันรหัสผ่าน
            if (password !== confirmPassword) {
                showNotify('<svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#e3e3e3"><path d="M600-240v-120H488q-32 54-87 87t-121 33q-100 0-170-70T40-480q0-100 70-170t170-70q66 0 121 33t87 87h272v80H434q-8-39-48-79.5T280-640q-66 0-113 47t-47 113q0 66 47 113t113 47q66 0 106-40.5t48-79.5h246v120h80v80H600ZM280-400q33 0 56.5-23.5T360-480q0-33-23.5-56.5T280-560q-33 0-56.5 23.5T200-480q0 33 23.5 56.5T280-400Zm0-80Zm600 240q-17 0-28.5-11.5T840-280q0-17 11.5-28.5T880-320q17 0 28.5 11.5T920-280q0 17-11.5 28.5T880-240Zm-40-160v-200h80v200h-80Z"/></svg> รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน', "error");
                addInputError(document.getElementById("confirmPassword"));
                isValid = false;
            }

            if (!isValid) {
                return false;
            }

            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader("Accept", "application/json");

            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log("Raw Server Response:", xhr.responseText);
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === "success") {
                            showNotify('<svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#e3e3e3"><path d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm34-102 102-44 104 44 56-96 110-26-10-112 74-84-74-86 10-112-110-24-58-96-102 44-104-44-56 96-110 24 10 112-74 86 74 84-10 114 110 24 58 96Zm102-318Zm-42 142 226-226-56-58-170 170-86-84-56 56 142 142Z"/></svg> สมัครสมาชิกสำเร็จ', "success");
                            setTimeout(function() {
                                form.reset();
                                location.reload();
                            }, 1000);
                        } else if (response.status === "error") {
                            if (response.message === 'ชื่อผู้ใช้นี้มีอยู่แล้วในระบบ') {
                                showNotify('<svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#e3e3e3"><path d="M800-520q-17 0-28.5-11.5T760-560q0-17 11.5-28.5T800-600q17 0 28.5 11.5T840-560q0 17-11.5 28.5T800-520Zm-40-120v-200h80v200h-80ZM360-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm80-80h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0-80Zm0 400Z"/></svg> ชื่อผู้ใช้นี้มีอยู่แล้วในระบบ', "error");
                            }
                        }
                    } catch (error) {
                        console.error("Error parsing JSON:", error);
                        showNotify("เกิดข้อผิดพลาดในการประมวลผลข้อมูล: " + error.message, "error");
                    }
                } else {
                    showNotify('เกิดข้อผิดพลาดในการส่งข้อมูล: ' + xhr.responseText, "error");
                }
            };

            xhr.send(formData);
            return false;
        }

        // ฟังก์ชั่นที่ทำงานเมื่อฟอร์มถูกส่ง (สำหรับการสร้าง admin)

        // ตรวจสอบความแข็งแรงของรหัสผ่าน
        const passwordInputAdmin = document.getElementById('password-admin');
        const checklistAdmin = {
            length: document.getElementById('length'),
            uppercase: document.getElementById('uppercase'),
            number: document.getElementById('number'),
            special: document.getElementById('special')
        };
        const progressBarAdmin = document.getElementById('password-progress-bar');

        passwordInputAdmin.addEventListener('input', function() {
            const value = this.value;
            let score = 0;

            if (value.length >= 8) {
                checklistAdmin.length.classList.add('valid');
                checklistAdmin.length.querySelector('.icon').textContent = '✔';
                score++;
            } else {
                checklistAdmin.length.classList.remove('valid');
                checklistAdmin.length.querySelector('.icon').textContent = '✖';
            }

            if (/[A-Z]/.test(value)) {
                checklistAdmin.uppercase.classList.add('valid');
                checklistAdmin.uppercase.querySelector('.icon').textContent = '✔';
                score++;
            } else {
                checklistAdmin.uppercase.classList.remove('valid');
                checklistAdmin.uppercase.querySelector('.icon').textContent = '✖';
            }

            if (/\d/.test(value)) {
                checklistAdmin.number.classList.add('valid');
                checklistAdmin.number.querySelector('.icon').textContent = '✔';
                score++;
            } else {
                checklistAdmin.number.classList.remove('valid');
                checklistAdmin.number.querySelector('.icon').textContent = '✖';
            }

            if (/[^A-Za-z0-9]/.test(value)) {
                checklistAdmin.special.classList.add('valid');
                checklistAdmin.special.querySelector('.icon').textContent = '✔';
                score++;
            } else {
                checklistAdmin.special.classList.remove('valid');
                checklistAdmin.special.querySelector('.icon').textContent = '✖';
            }

            progressBarAdmin.style.width = `${(score / 4) * 100}%`;
            progressBarAdmin.style.backgroundColor = score >= 3 ? '#4CAF50' : '#f44336';
        });

        document.getElementById('adminSignupForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const fileInput = document.getElementById('upload-img-admin');
            const file = fileInput.files[0];
            const gmail = document.getElementById('gmail-admin').value.trim();
            const password = document.getElementById('password-admin').value;
            const confirmPassword = document.getElementById('confirmPassword-admin').value;
            const firstName = document.getElementById('firstName-admin').value.trim();
            const lastName = document.getElementById('lastName-admin').value.trim();

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!gmail || !password || !confirmPassword || !firstName || !lastName) {
                showAlertToast("กรุณากรอกข้อมูลให้ครบถ้วน", icons.error, "error-toast");
                return;
            }

            if (!emailRegex.test(gmail)) {
                showAlertToast("รูปแบบอีเมลไม่ถูกต้อง", icons.error, "error-toast");
                return;
            }

            if (!file) {
                showAlertToast("กรุณาเลือกไฟล์รูปภาพของแอดมิน", icons.error, "error-toast");
                return;
            }

            if (password !== confirmPassword) {
                showAlertToast("รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน", icons.error, "error-toast");
                return;
            }

            try {
                const base64 = await toBase64(file);
                const formData = new FormData();
                formData.append('gmail', gmail);
                formData.append('password', password);
                formData.append('confirmPassword', confirmPassword);
                formData.append('firstName', firstName);
                formData.append('lastName', lastName);
                formData.append('profileImage', base64);

                const response = await fetch('/sci-next/admin/dashboard_superadmin/manageAdmin/adminSignup.php', {
                    method: 'POST',
                    body: formData
                });

                const contentType = response.headers.get("content-type") || "";
                const responseText = await response.text();
                console.log("Response text:", responseText);

                if (contentType.includes("application/json")) {
                    const data = JSON.parse(responseText);

                    if (data.status === 'error') {
                        if (data.message.includes("อีเมล") && data.message.includes("ใช้ไปแล้ว")) {
                            showAlertToast("อีเมลนี้ถูกใช้งานแล้ว", icons.error, "error-toast");
                        } else {
                            showAlertToast(data.message || "เกิดข้อผิดพลาด", icons.error, "error-toast");
                        }
                    }

                    if (data.status === 'success') {
                        showAlertToast("สมัครสมาชิกแอดมินสำเร็จ!", icons.success, "success-toast");
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                        this.reset();
                        document.getElementById('file-name').textContent = '';
                        if (typeof progressBar !== 'undefined') {
                            progressBar.style.width = '0%';
                        }
                    }
                } else {
                    showAlertToast("ไม่สามารถประมวลผลข้อมูลได้", icons.error, "error-toast");
                }
            } catch (error) {
                console.error('Error:', error);
                showAlertToast("เกิดข้อผิดพลาดในการส่งข้อมูล", icons.error, "error-toast");
            }
        });


        // ฟังก์ชันแปลงไฟล์เป็น base64
        function toBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        }

        const passwordInputEmpolyee = document.getElementById('password-employee');
        const checklistEmpolyee = {
            length: document.getElementById('length'),
            uppercase: document.getElementById('uppercase'),
            number: document.getElementById('number'),
            special: document.getElementById('special')
        };
        const progressBarEmpolyee = document.getElementById('password-progress-bar');

        passwordInputEmpolyee.addEventListener('input', function() {
            const value = this.value;
            let score = 0;

            if (value.length >= 8) {
                checklistEmpolyee.length.classList.add('valid');
                checklistEmpolyee.length.querySelector('.icon').textContent = '✔';
                score++;
            } else {
                checklistEmpolyee.length.classList.remove('valid');
                checklistEmpolyee.length.querySelector('.icon').textContent = '✖';
            }

            if (/[A-Z]/.test(value)) {
                checklistEmpolyee.uppercase.classList.add('valid');
                checklistEmpolyee.uppercase.querySelector('.icon').textContent = '✔';
                score++;
            } else {
                checklistEmpolyee.uppercase.classList.remove('valid');
                checklistEmpolyee.uppercase.querySelector('.icon').textContent = '✖';
            }

            if (/\d/.test(value)) {
                checklistEmpolyee.number.classList.add('valid');
                checklistEmpolyee.number.querySelector('.icon').textContent = '✔';
                score++;
            } else {
                checklistEmpolyee.number.classList.remove('valid');
                checklistEmpolyee.number.querySelector('.icon').textContent = '✖';
            }

            if (/[^A-Za-z0-9]/.test(value)) {
                checklistEmpolyee.special.classList.add('valid');
                checklistEmpolyee.special.querySelector('.icon').textContent = '✔';
                score++;
            } else {
                checklistEmpolyee.special.classList.remove('valid');
                checklistEmpolyee.special.querySelector('.icon').textContent = '✖';
            }

            progressBarEmpolyee.style.width = `${(score / 4) * 100}%`;
            progressBarEmpolyee.style.backgroundColor = score >= 3 ? '#4CAF50' : '#f44336';
        });


        document.getElementById('employeeSignupForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const fileInput = document.getElementById('upload-img-employee');
            const file = fileInput.files[0];
            const gmail = document.getElementById('gmail-employee').value.trim();
            const password = document.getElementById('password-employee').value;
            const confirmPassword = document.getElementById('confirmPassword-employee').value;
            const firstName = document.getElementById('firstName-employee').value.trim();
            const lastName = document.getElementById('lastName-employee').value.trim();

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!gmail || !password || !confirmPassword || !firstName || !lastName || !file) {
                showAlertToast("กรุณากรอกข้อมูลให้ครบถ้วน", icons.error, "error-toast");
                return;
            }

            if (!emailRegex.test(gmail)) {
                showAlertToast("รูปแบบอีเมลไม่ถูกต้อง", icons.warning, "warning-toast");
                return;
            }

            if (password !== confirmPassword) {
                showAlertToast("รหัสผ่านไม่ตรงกัน", icons.warning, "warning-toast");
                return;
            }

            // เช็คขนาดไฟล์
            if (file.size > 1 * 1024 * 1024) { // 1MB
                showAlertToast("ขนาดรูปภาพต้องไม่เกิน 1MB", icons.warning, "warning-toast");
                return;
            }

            try {
                // แปลงไฟล์เป็น base64
                const base64 = await toBase64(file);
                const formData = new FormData();
                formData.append('gmail', gmail);
                formData.append('password', password);
                formData.append('confirmPassword', confirmPassword);
                formData.append('firstName', firstName);
                formData.append('lastName', lastName);
                formData.append('profileImage', file);


                const response = await fetch('/sci-next/admin/dashboard_superadmin/manageEmployee/employeeSignup.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showAlertToast("สมัครพนักงานสำเร็จ", icons.success, "success-toast");
                    document.getElementById('employeeSignupForm').reset();
                } else {
                    showAlertToast(result.message || "เกิดข้อผิดพลาด", icons.error, "error-toast");
                }
            } catch (error) {
                console.error('Error:', error);
                showAlertToast("ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้", icons.error, "error-toast");
            }
        });

        // ค้นหาเมนูทั้งหมดที่มีคลาส .tab
        const tabs = document.querySelectorAll('.tab');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // ลบคลาส 'selected' ออกจากทุกๆ tab
                tabs.forEach(t => t.classList.remove('selected'));

                // เพิ่มคลาส 'selected' ให้กับ tab ที่ถูกคลิก
                tab.classList.add('selected');
            });
        });

        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const body = document.body;
            const openIcon = document.querySelector('.open-icon');
            const closeIcon = document.querySelector('.close-icon');

            // สลับคลาสเพื่อให้ Sidebar ยุบ/ขยาย
            sidebar.classList.toggle('closed');
            body.classList.toggle('sidebar-collapsed');

            // เปลี่ยนไอคอนให้แสดง/ซ่อนตามสถานะของ Sidebar
            if (sidebar.classList.contains('closed')) {
                openIcon.style.display = "none";
                closeIcon.style.display = "inline-block";
            } else {
                openIcon.style.display = "inline-block";
                closeIcon.style.display = "none";
            }
        }

        document.querySelectorAll('.category-btn').forEach(button => {
            button.addEventListener('click', function() {
                // ลบคลาส selected ออกจากทุกปุ่ม
                document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('selected'));

                // เพิ่มคลาส selected ให้ปุ่มที่ถูกกด
                this.classList.add('selected');

                // อัปเดตค่าใน input hidden
                document.getElementById('productCategory').value = this.getAttribute('data-category');
            });
        });

        document.querySelector('.collapsible-toggle').addEventListener('click', function() {
            const menu = document.getElementById('menu');
            menu.classList.toggle('active'); // เพิ่มหรือเอาคลาส active ออก
            const isExpanded = menu.classList.contains('active');
            this.setAttribute('aria-expanded', isExpanded); // อัพเดตค่า aria-expanded
        });

        // อัปเดตจำนวนสินค้าในตะกร้า
        function updateOrderCount(count) {
            document.querySelector(".badge").textContent = count;
        }

        // JavaScript (การคำนวณจำนวนที่เลือก):
        // ตัวแปรที่เก็บ element ของ checkbox
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const selectedCountText = document.getElementById('selected-count');

        // ฟังก์ชั่นในการอัพเดทจำนวนที่เลือก
        function updateSelectedCount(category, count) {
            const countElement = document.getElementById(`${category}-selected-count`);
            if (countElement) {
                countElement.textContent = count;
            }
        }

        // เพิ่ม event listener ให้ทุก checkbox
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        // เรียกใช้ฟังก์ชั่นครั้งแรกเพื่อให้ข้อมูลถูกต้องตอนโหลด
        updateSelectedCount();


        document.addEventListener("DOMContentLoaded", () => {
            // debounce function
            function debounce(func, wait = 200) {
                let timeout;
                return (...args) => {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func(...args), wait);
                };
            }

            // helper validate functions
            const validators = {
                notEmpty: value => value.trim() !== "",
                email: value => /^\S+@\S+\.\S+$/.test(value),
                minLength: (value, len) => value.length >= len,
                numberPositive: value => !isNaN(value) && Number(value) > 0,
                passwordsMatch: (pass, confirm) => pass === confirm
            };

            // update error message element
            function showError(input, message) {
                let errorEl = input.nextElementSibling;
                if (!errorEl || !errorEl.classList.contains("error-message")) {
                    errorEl = document.createElement("small");
                    errorEl.className = "error-message";
                    input.parentNode.insertBefore(errorEl, input.nextSibling);
                }
                errorEl.textContent = message;
                input.classList.toggle("input-error", !!message);
            }

            // clear error message
            function clearError(input) {
                const errorEl = input.nextElementSibling;
                if (errorEl && errorEl.classList.contains("error-message")) {
                    errorEl.textContent = "";
                }
                input.classList.remove("input-error");
            }

            // function bind input and preview with validation
            function bindInputWithValidation(inputId, previewId, options = {}) {
                const input = document.getElementById(inputId);
                const preview = document.getElementById(previewId);
                if (!input || !preview) return;

                // click on preview to focus input
                preview.style.cursor = "pointer";
                preview.addEventListener("click", () => {
                    input.scrollIntoView({
                        behavior: "smooth",
                        block: "center"
                    });
                    input.focus();
                });

                const validateAndUpdate = () => {
                    const value = input.value;
                    let errorMsg = "";

                    if (options.required && !validators.notEmpty(value)) {
                        errorMsg = "";
                    } else if (options.type === "email" && !validators.email(value)) {
                        errorMsg = "รูปแบบอีเมลไม่ถูกต้อง";
                    } else if (options.minLength && !validators.minLength(value, options.minLength)) {
                        errorMsg = `ต้องมีอย่างน้อย ${options.minLength} ตัวอักษร`;
                    } else if (options.type === "numberPositive" && !validators.numberPositive(value)) {
                        errorMsg = "กรุณากรอกตัวเลขมากกว่า 0";
                    } else if (options.matchWith) {
                        // กรณีต้องเทียบกับ input ตัวอื่น เช่น password-confirm
                        const otherInput = document.getElementById(options.matchWith);
                        if (otherInput && !validators.passwordsMatch(value, otherInput.value)) {
                            errorMsg = "รหัสผ่านไม่ตรงกัน";
                        }
                    }

                    if (errorMsg) {
                        showError(input, errorMsg);
                        preview.classList.add("preview-error");
                    } else {
                        clearError(input);
                        preview.classList.remove("preview-error");
                    }

                    // อัปเดตข้อความ preview (mask password ถ้าต้องการ)
                    if (options.maskPassword && value) {
                        preview.textContent = "●".repeat(value.length);
                    } else if (options.formatNumber) {
                        preview.textContent = Number(value).toLocaleString() || "";
                    } else {
                        preview.textContent = value;
                    }
                };

                input.addEventListener("input", debounce(validateAndUpdate, 250));
                validateAndUpdate();
            }

            // Bind product inputs
            bindInputWithValidation("productName", "previewNameProduct", {
                required: true
            });
            bindInputWithValidation("barcode", "previewBarcodeProduct", {
                required: true
            });
            bindInputWithValidation("productPrice", "previewPriceProduct", {
                type: "numberPositive",
                required: true,
                formatNumber: true
            });
            bindInputWithValidation("productCost", "previewCostProduct", {
                type: "numberPositive",
                required: true,
                formatNumber: true
            });
            bindInputWithValidation("productStock", "previewStockProduct", {
                type: "numberPositive",
                required: true,
                formatNumber: true
            });
            bindInputWithValidation("productReorderLevel", "previewReorderLevelProduct", {
                type: "numberPositive",
                required: true,
                formatNumber: true
            });

            // Bind admin inputs with validation
            bindInputWithValidation("firstName-admin", "previewFirstNameAdmin", {
                required: true
            });
            bindInputWithValidation("lastName-admin", "previewLastNameAdmin", {
                required: true
            });
            bindInputWithValidation("gmail-admin", "previewGmailAdmin", {
                required: true,
                type: "email"
            });
            bindInputWithValidation("password-admin", "previewPasswordAdmin", {
                required: true,
                minLength: 6,
                maskPassword: true
            });
            bindInputWithValidation("confirmPassword-admin", "previewConfirmPasswordAdmin", {
                required: true,
                maskPassword: true,
                matchWith: "password-admin"
            });

            // Bind employee inputs with validation
            bindInputWithValidation("firstName-employee", "previewFirstNameEmployee", {
                required: true
            });
            bindInputWithValidation("lastName-employee", "previewLastNameEmployee", {
                required: true
            });
            bindInputWithValidation("gmail-employee", "previewGmailEmployee", {
                required: true,
                type: "email"
            });
            bindInputWithValidation("password-employee", "previewPasswordEmployee", {
                required: true,
                minLength: 6,
                maskPassword: true
            });
            bindInputWithValidation("confirmPassword-employee", "previewConfirmPasswordEmployee", {
                required: true,
                maskPassword: true,
                matchWith: "password-employee"
            });


            // image input preview from URL with basic validation + error message
            const productImageInput = document.getElementById("productImage");
            const productImagePreview = document.getElementById("previewImageProduct");
            const productImageErrorEl = document.createElement("small");
            productImageErrorEl.className = "error-message";
            productImageInput.parentNode.appendChild(productImageErrorEl);

            productImageInput.addEventListener("input", () => {
                const url = productImageInput.value.trim();

                if (!url) {
                    productImageErrorEl.textContent = "";
                    productImagePreview.src = "/sci-next/img/product_food1.png";
                    return;
                }

                // ลองโหลดรูปทันทีแบบไม่ตรวจ regex
                productImageErrorEl.textContent = "";
                productImagePreview.onerror = () => {
                    productImageErrorEl.textContent = "ไม่สามารถโหลดรูปภาพจาก URL นี้ได้";
                    productImagePreview.src = "/sci-next/img/product_food1.png";
                };
                productImagePreview.onload = () => {
                    productImageErrorEl.textContent = "";
                };
                productImagePreview.src = `${url}?t=${Date.now()}`;
            });


            // image admin preview from file input
            const adminImageInput = document.getElementById("upload-img-admin");
            const adminImagePreview = document.getElementById("previewImageAdmin");
            if (adminImageInput && adminImagePreview) {
                adminImageInput.addEventListener("change", () => {
                    const file = adminImageInput.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = e => adminImagePreview.src = e.target.result;
                        reader.readAsDataURL(file);
                    }
                });
            }

            // image employee preview from file input
            const employeeImageInput = document.getElementById("upload-img-employee");
            const employeeImagePreview = document.getElementById("previewImageEmployee");
            if (employeeImageInput && employeeImagePreview) {
                employeeImageInput.addEventListener("change", () => {
                    const file = employeeImageInput.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = e => employeeImagePreview.src = e.target.result;
                        reader.readAsDataURL(file);
                    }
                });
            }
        });

        function showLogoutModal(event) {
            event.preventDefault();
            document.getElementById("logoutConfirmModal").classList.remove("hidden");
        }

        document.getElementById("confirmLogoutBtn").addEventListener("click", function() {
            window.location.href = "/sci-next/Logout.php";
        });

        document.getElementById("cancelLogoutBtn").addEventListener("click", function() {
            document.getElementById("logoutConfirmModal").classList.add("hidden");
        });

        // ปิด modal เมื่อคลิกนอก modal
        window.addEventListener("click", function(event) {
            const modal = document.getElementById("logoutConfirmModal");
            if (event.target === modal) {
                modal.classList.add("hidden");
            }
        });

        const input = document.getElementById('productImage');
        const clearBtn = document.querySelector('.clear-btn');

        function toggleClearBtn() {
            clearBtn.style.display = input.value.trim() ? 'block' : 'none';
        }

        function clearInput() {
            input.value = '';
            clearBtn.style.display = 'none';
            input.focus();
        }

        // เริ่มต้นตรวจสอบตอนโหลดหน้า (ถ้ามีค่าใน input)
        toggleClearBtn();

        const originalEmail = "<?= htmlspecialchars($currentAdmin['gmail']) ?>"; // จาก PHP
        let maskedEmail = "";

        // ฟังก์ชัน mask email
        function maskEmail(email) {
            const [name, domain] = email.split('@');
            if (!name || !domain) return email;

            if (name.length <= 2) {
                return '*'.repeat(name.length) + '@' + domain;
            } else {
                return name.slice(0, 2) + '*'.repeat(name.length - 2) + '@' + domain;
            }
        }

        // สลับแสดง email แบบ masked กับ email จริง
        function toggleEmailVisibility() {
            const emailInput = document.getElementById('username-adminProfile');
            const realEmail = document.getElementById('real-gmail').value;
            const emailEyeIcon = document.getElementById('emailEyeIcon');

            // อ่าน masked email จาก attribute data-masked-email
            const maskedEmailAttr = emailInput.dataset.maskedEmail;

            if (emailInput.value === maskedEmailAttr) {
                // แสดง email จริง
                emailInput.value = realEmail;
                emailEyeIcon.textContent = 'visibility';
            } else {
                // แสดง masked email
                emailInput.value = maskedEmailAttr;
                emailEyeIcon.textContent = 'visibility_off';
            }
        }

        // เมื่อโหลดหน้าเว็บ กำหนดค่าเริ่มต้นของ email input เป็น masked พร้อมเก็บไว้ใน attribute data-masked-email
        window.addEventListener("DOMContentLoaded", () => {
            maskedEmail = maskEmail(originalEmail);
            const emailInput = document.getElementById("username-adminProfile");
            emailInput.value = maskedEmail;
            emailInput.dataset.maskedEmail = maskedEmail; // เก็บ masked email ไว้ใน attribute เพื่อใช้ toggle
        });

        // เช็คการ submit form ว่า gmail เปลี่ยนจากค่าจริงไหม
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('profile-form');
            const emailInput = form.querySelector('input[name="gmail"]');
            const realEmailInput = document.getElementById('real-gmail');

            const originalEmail = realEmailInput.value.trim();

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // เปิดลูกตาก่อน (ถ้ายังเป็น masked)
                const emailInput = form.querySelector('input[name="gmail"]');
                const realEmail = document.getElementById('real-gmail').value;
                const maskedEmail = emailInput.dataset.maskedEmail;

                if (emailInput.value === maskedEmail) {
                    emailInput.value = realEmail; // เปลี่ยนเป็น Gmail จริง
                    document.getElementById('emailEyeIcon').textContent = 'visibility'; // เปลี่ยนไอคอน
                }

                const currentEmail = emailInput.value.trim();

                // ถ้ายังเป็น masked อยู่ (ยังไม่ได้เปิดลูกตาเอง + แก้) ให้เตือน
                if (currentEmail === maskedEmail) {
                    showAlertToast("กรุณาแก้ไข Gmail ก่อนส่ง", icons.error, "error-toast");
                    return;
                }

                // ส่ง form ตามปกติ
                const formData = new FormData(form);

                const fileInput = document.getElementById('file-input');
                if (fileInput && fileInput.files[0]) {
                    formData.append('profile_image', fileInput.files[0]);
                }

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                    })
                    .then(res => res.text())
                    .then(data => {
                        if (data === 'success' || data === 'อัปเดตข้อมูลสำเร็จ') {
                            showAlertToast("อัพเดตข้อมูลสำเร็จ", icons.success, "success-toast");
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showAlertToast('เกิดข้อผิดพลาด: ' + data, icons.error, "error-toast");
                        }
                    });
            });
        });

        document.getElementById('order-date-picker').addEventListener('change', function() {
            const selectedDate = this.value; // YYYY-MM-DD
            renderFilteredOrders(selectedDate);
        });

        let cachedOrdersByDate = {};

        async function loadOrders() {
            try {
                const res = await fetch('../../controller/controllerSale/controllerSaleOrder.php');
                const data = await res.json();

                if (!data.success) {
                    console.error('โหลดข้อมูลล้มเหลว:', data.message);
                    return;
                }

                const orders = data.orders;
                const grouped = {};

                for (const orderId in orders) {
                    const order = orders[orderId];
                    const dateKey = order.order_date.split(' ')[0]; // yyyy-mm-dd

                    if (!grouped[dateKey]) grouped[dateKey] = [];

                    grouped[dateKey].push(...order.items.map(item => ({
                        ...item,
                        time: new Date(order.order_date).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        }),
                        fullDate: new Date(order.order_date).toLocaleDateString('th-TH', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        })
                    })));
                }

                cachedOrdersByDate = grouped;
                renderFilteredOrders(); // แสดงทั้งหมดตอนโหลด
            } catch (err) {
                console.error('เกิดข้อผิดพลาด:', err);
            }
        }

        function renderFilteredOrders(dateFilter = null) {
            const container = document.getElementById('order-container');
            container.innerHTML = '';

            const dates = Object.keys(cachedOrdersByDate).sort().reverse();
            const datesToRender = dateFilter ? [dateFilter] : dates;

            datesToRender.forEach(date => {
                const items = cachedOrdersByDate[date];
                if (!items) return;

                let dailyTotal = 0;

                // 🔵 หัวตารางแต่ละวัน
                const heading = document.createElement('h2');
                heading.textContent = `วันที่ ${items[0].fullDate}`;
                heading.style.textAlign = 'center';
                heading.style.margin = '20px 0 10px';
                heading.style.color = '#fff';
                container.appendChild(heading);

                // 🔵 สร้าง table ใหม่ต่อวัน
                const table = document.createElement('table');
                table.innerHTML = `
            <thead>
                <tr>
                    <th>ชื่อสินค้า</th>
                    <th>รูปสินค้า</th>
                    <th>ราคา</th>
                    <th>จำนวน</th>
                    <th>ราคารวม</th>
                    <th>เวลา</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align:right;"><b>ยอดรวมทั้งวัน</b></td>
                    <td colspan="2" style="font-weight:bold;" id="total-${date}"></td>
                </tr>
            </tfoot>
        `;

                const tbody = table.querySelector('tbody');

                items.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                <td>${item.name}</td>
                <td><img src="${item.image_url}" style="border-radius:5px; width:60px;" alt="รูปสินค้า"></td>
                <td>${parseFloat(item.price).toFixed(2)} บาท</td>
                <td>${item.quantity} ชิ้น</td>
                <td>${item.subtotal.toFixed(2)} บาท</td>
                <td>${item.time}</td>
            `;
                    tbody.appendChild(tr);
                    dailyTotal += item.subtotal;
                });

                table.querySelector(`#total-${date}`).innerText = `${dailyTotal.toFixed(2)} บาท`;
                container.appendChild(table);
            });

            // ถ้าเลือกวันที่แล้วไม่มีรายการ
            if (dateFilter && !cachedOrdersByDate[dateFilter]) {
                container.innerHTML = `<p style="text-align:center; color:#fff;">ไม่มีรายการในวันที่เลือก</p>`;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadOrders();

            document.getElementById('order-date-picker').addEventListener('change', e => {
                const selectedDate = e.target.value;
                renderFilteredOrders(selectedDate || null);
            });
        });
    </script>
</body>

</html>