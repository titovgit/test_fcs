<?php
$title = 'Тест для php-разработчиков';
$author = 'John Titov';
$date = '2022';
$test = true;

?>
<!DOCTYPE html>
<html lang="ru" class="h-100">
    <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?= $title ?></title>
	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" href="/favicon.svg" type="image/svg+xml">
	<meta name="robots" content="noindex"/>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
	      integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"
	      >
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ru.min.js"></script>		
    </head>
    <body class="d-flex flex-column h-100 bg-light">
	<main class="flex-shrink-0">
	    <div class="container">
		<h1 class="my-4"><?= $title ?></h1>
		<div class="alert alert-info my-4">
		    <a href="https://fsa.gov.ru/" target="_blank">Федеральная служба по аккредитации</a>
		</div>
		<section class="card my-4">
		    <h2 class="card-header">Поиск деклараций</h2>
		    <div class="card-body">
			<form id="form-filter">
			    <div class="row">
				<div class="col">
				    <label class="form-label" for="state">Номер декларации о соответствии</label>
				    <input type="text" class="form-control" id="number" name="number">
				</div>
				<div class="col">
				    <label class="form-label" for="state">Статус</label>
				    <select class="form-select" id="state" name="state">
					<option value="">- любой -</option>
				    </select>
				</div>
				<div class="col">
				    <label class="form-label" for="state">Дата регистрации с</label>
				    <input type="text" class="form-control datepicker" id="regDateMin" name="regDateMin">
				</div>
				<div class="col">
				    <label class="form-label" for="state">Дата регистрации по</label>
				    <input type="text" class="form-control datepicker" id="regDateMax" name="regDateMax">
				</div>
				<div class="col">
				    <label class="form-label" for="state">Дата окончания действия с</label>
				    <input type="text" class="form-control datepicker" id="endDateMin" name="endDateMin">
				</div>
				<div class="col">
				    <label class="form-label" for="state">Дата окончания действия по</label>
				    <input type="text" class="form-control datepicker" id="endDateMax" name="endDateMax">
				</div>
				<div class="col">
				    <label class="form-label" for="state">Размер списка деклараций</label>
				    <select class="form-select" id="size" name="size">
					<option value="25">25</option>
					<option value="50">50</option>
					<option value="100">100</option>
				    </select>
				</div>				
				<div class="col">
				    <button class="btn btn-primary" type="submit">Найти</button>
				</div>
			    </div>
			</form>
		    </div>
		</section>
		<section class="card my-4">
		    <h2 class="card-header">Список деклараций</h2>
		    <div class="card-body">
			<div class="table-responsive table-striped table-sm">
			    <table id="declaration-list"  class="table">
				<thead>
				    <tr>
					<th>id</th>
					<th>Статус</th>
					<th>Номер</th>
					<th>Дата регистрации</th>
					<th>Дата окончания действия</th>
					<th>Наименование продукции</th>
					<th>Заявитель</th>
					<th>Изготовитель</th>
					<th>Происхождение продукции</th>
					<th>Тип объекта декларирования</th>
				    </tr>
				</thead>
				<tbody>
				</tbody>
			    </table>
			</div>
		    </div>
		</section>
	    </div>
	</main>
	<footer class="footer mt-auto py-3 bg-dark">
	    <div class="container">
		<span class="text-muted"><?= "$date. $author" ?></span>
	    </div>
	</footer>
	<script src="/js/ajax.js"></script>
	
	
	<script>
	    $('.datepicker').datepicker({
		autoclose: true,
		format: "dd.mm.yyyy",
		language: "ru"		
	    })
	    var key = localStorage.getItem('key');

	    $(document).ready(function(){
		getFilters();
		
		$('#form-filter').on('submit',function(){
		    var form = $(this).serialize()
		    getData(form);
		    return false;
		})
	    })
	</script>
    </body>
</html>
