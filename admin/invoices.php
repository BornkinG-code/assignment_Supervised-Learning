<?php require_once __DIR__ . '/_auth.php'; ?>
<!doctype html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width,initial-scale=1'>
  <title>Invoices</title>
  <link rel='preconnect' href='https://fonts.googleapis.com'>
  <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
  <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap' rel='stylesheet'>
  <link rel='stylesheet' href='../assets/css/style.css'>
</head>
<body class='admin-portal'>
<div class='admin-screen'>
  <header class='admin-topbar glass'>
    <div><p class='eyebrow'>Finance</p><h1>Invoices</h1></div>
    <a class='btn alt' href='index.php'>Dashboard</a>
  </header>

  <section class='panel'>
    <div class='panel-head'><h2>Invoice Archive</h2><span class='pill'>Monthly records</span></div>
    <table class='table'>
      <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Month</th><th>File</th><th>Delete</th></tr></thead>
      <tbody id='rows'></tbody>
    </table>
  </section>
</div>
<script>
async function load(){const r=await fetch('../api/admin_invoices.php');const d=await r.json();rows.innerHTML=d.invoices.map(i=>`<tr><td>${i.order_code}</td><td>${i.customer_name}</td><td>₹${i.total_amount}</td><td>${i.invoice_month}</td><td><a href='../${i.file_path}' download>Download</a></td><td><button class='btn bad' onclick='del(${i.id})'>Delete</button></td></tr>`).join('')}
async function del(id){await fetch('../api/admin_invoices.php',{method:'DELETE',body:`id=${id}`});load()}
load();
</script></body></html>
