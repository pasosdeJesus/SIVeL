# Read about factories at https://github.com/thoughtbot/factory_girl

FactoryGirl.define do
  factory :municipio do
    nombre "Municipio1"
    latitud 1.5
    longitud 1.5
    fechacreacion "2014-08-04"
    fechadeshabilitacion "2014-08-04"
		id 1
    id_pais 1
    association :departamento, factory: :departamento
  end
end
