<h2 class="principal">Disciplinas</h2>

<?php 
echo form_open("disciplina/altera");
echo form_hidden("disciplina_id", $disciplina['id']);

echo form_label("Nome do disciplina", "nome");
echo form_input(array(
	"name" => "nome",
	"id" => "nome",
	"type" => "text",
	"class" => "form-campo",
	"maxlength" => "255",
	"value" => set_value("nome", $disciplina['nome'])
));
echo form_error("nome");

echo "<br>";

echo form_fieldset("Departamento", array("class"=>"form-select"));
echo "<br>";
echo form_dropdown('departamentos', $departamentos, $selecionado);
echo form_fieldset_close();

echo "<br>";
echo "<br>";

echo form_button(array(
	"class" => "btn btn-primary",
	"content" => "Alterar",
	"type" => "sumbit"
));

echo form_close();
?>
