<?php require_once __DIR__ . '/_auth.php'; ?>
<!doctype html><html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>Invoices</title><link rel='stylesheet' href='../assets/css/style.css'></head>
<body><div class='container'><div class='header'><div class='brand'>Invoices</div><a class='btn alt' href='index.php'>Back</a></div>
<div class='card'><div class='card-body'><table class='table'><thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Month</th><th>File</th><th>Delete</th></tr></thead><tbody id='rows'></tbody></table></div></div></div>
<script>
async function load(){const r=await fetch('../api/admin_invoices.php');const d=await r.json();rows.innerHTML=d.invoices.map(i=>`<tr><td data-label='Order'>${i.order_code}</td><td data-label='Customer'>${i.customer_name}</td><td data-label='Total'>₹${i.total_amount}</td><td data-label='Month'>${i.invoice_month}</td><td data-label='File'><a href='../${i.file_path}' download>Download</a></td><td data-label='Delete'><button class='btn bad' onclick='del(${i.id})'>Delete</button></td></tr>`).join('')}
async function del(id){await fetch('../api/admin_invoices.php',{method:'DELETE',body:`id=${id}`});load()}
load();
</script></body></html>
