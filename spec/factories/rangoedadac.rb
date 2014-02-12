# Read about factories at https://github.com/thoughtbot/factory_girl

FactoryGirl.define do
  factory :rangoedadac do
    nombre "MyString"
    limiteinferior 1
    limitesuperior 1
    fechacreacion "2014-02-11"
    fechadeshabilitacion "2014-02-11"
  end
end
