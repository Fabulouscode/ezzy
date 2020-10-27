<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice Order</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  </head>
  <body>
    <table class="table table-bordered">
    <thead>
      <tr class="table-danger">
        <td>Medicine Name</td>
        <td>Medicine SKU</td>
        <td>Quanity</td>
        <td>MRP Price</td>
        <td>Offer Price</td>
      </tr>
      </thead>
      <tbody>
        @foreach ($data->orderProductDetails as $order_product)
        <tr>
            <td>{{ $order_product->medicineDetails->medicine_name }}</td>
            <td>{{ $order_product->medicineDetails->medicine_sku }}</td>
            <td>{{ $order_product->quantity }}</td>
            <td>{{ $order_product->shopMedicineDetails->mrp_price }}</td>
            <td>{{ $order_product->shopMedicineDetails->offer_price }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </body>
</html>