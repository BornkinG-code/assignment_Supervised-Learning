<?php require_once __DIR__ . '/_auth.php'; ?>
<!doctype html>
<html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>Invoices</title><link rel='stylesheet' href='../assets/css/style.css'></head>
<body class='admin-page'><div class='container admin-shell'>
<header class='admin-topbar card'><div><div class='brand'>Invoices</div><div class='subtle'>Track downloadable invoice history</div></div><a class='btn btn-ghost' href='index.php'>Dashboard</a></header>
<nav class='admin-nav card'><a class='btn btn-ghost' href='index.php'>Dashboard</a><a class='btn btn-ghost' href='menu.php'>Menu</a><a class='btn btn-ghost' href='tables.php'>Tables</a><a class='btn btn-ghost active' href='invoices.php'>Invoices</a><a class='btn btn-ghost' href='settings.php'>Settings</a></nav>
<section class='card'><div class='card-body'><table class='table'><thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Month</th><th>File</th><th>Delete</th></tr></thead><tbody id='rows'></tbody></table></div></section>
</div>
<script>
async function load(){const r=await fetch('../api/admin_invoices.php');const d=await r.json();rows.innerHTML=d.invoices.map(i=>`<tr><td>${i.order_code}</td><td>${i.customer_name}</td><td>₹${i.total_amount}</td><td>${i.invoice_month}</td><td><a href='../${i.file_path}' download>Download</a></td><td><button class='btn btn-danger' onclick='del(${i.id})'>Delete</button></td></tr>`).join('')}
async function del(id){await fetch('../api/admin_invoices.php',{method:'DELETE',body:`id=${id}`});load()}load();
</script></body></html>
