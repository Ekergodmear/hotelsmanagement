<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<!-- Custom CSS -->
<style>
    .hero-section {
        position: relative;
        background-color: #1a237e;
        padding: 60px 0;
        color: white;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('https://images.unsplash.com/photo-1566073771259-6a8506099945') center/cover;
        opacity: 0.4;
    }
    .search-box {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        margin-top: -50px;
        position: relative;
        z-index: 100;
    }
    .destination-card {
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }
    .destination-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }
    .destination-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .hotel-card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hotel-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }
    .hotel-card img {
        height: 200px;
        object-fit: cover;
    }
    .rating {
        background: #0071c2;
        color: white;
        padding: 4px 8px;
        border-radius: 5px;
        font-weight: bold;
    }
    .navbar {
        background-color: #1a237e !important;
    }
    .nav-link {
        color: white !important;
    }
    .btn-search {
        background-color: #0071c2;
        color: white;
        padding: 10px 30px;
    }
    .hotel-card .btn-outline-primary {
        transition: all 0.3s ease;
    }
    .hotel-card .btn-outline-primary:hover {
        background-color: #0071c2;
        border-color: #0071c2;
        color: white;
    }
    .hotel-link {
        color: inherit;
        text-decoration: none;
    }
    .hotel-link:hover {
        color: inherit;
    }
</style>
