<h2 class="principal">Disciplinas</h2>

<table class="table table-striped table-bordered">
	<tr>
		<td><h3 class="text-center">Disciplinas cadastradas</h3></td>
		<td><h3 class="text-center">Departamento</h3></td>
		<td></td>
	</tr>

<?php foreach ($disciplinas as $disciplina) { ?>
	<tr>
		<td><?=$disciplina['nome']?></td>

		<td><?=$disciplina['departamento_id']?></td>

		<td>
		<?=anchor("disciplinas/{$disciplina['id']}", "Editar", array(
			"class" => "btn btn-primary btn-editar",
			"type" => "sumbit",
			"content" => "Editar"
		))?>
		
		<?php 
		echo form_open("disciplina/remove");
		echo form_hidden("disciplina_id", $disciplina['id']);
		echo form_button(array(
			"class" => "btn btn-danger btn-remover",
			"type" => "sumbit",
			"content" => "Remover"
		));
		echo form_close();
		?>
		</td>
	</tr>
<?php } ?>
</table>

<br><br>

<?php 
echo form_open("disciplina/novo");

echo form_label("Cadastrar um nova disciplina", "nome");
echo form_input(array(
	"name" => "nome",
	"id" => "nome",
	"type" => "text",
	"class" => "form-campo",
	"placeholder" => "Nome",
	"maxlength" => "255"
));
echo form_error("nome");

echo "<br>";

echo form_fieldset("Departamento", array("class"=>"form-select"));
echo "<br>";
echo form_dropdown('departamentos', $opcoes);
echo form_fieldset_close();

echo "<br>";
echo "<br>";

echo form_button(array(
	"class" => "btn btn-primary",
	"type" => "sumbit",
	"content" => "Cadastrar"
));

echo form_close();
?>