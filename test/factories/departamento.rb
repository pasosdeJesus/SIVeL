# Read about factories at https://github.com/thoughtbot/factory_girl

FactoryGirl.define do
  factory :departamento do
    id 1
    nombre "Departamento1"
    latitud 1.5
    longitud 1.5
    fechacreacion "2014-08-04"
    fechadeshabilitacion "2014-08-04"
    association :pais, factory: :pais
  end
end
