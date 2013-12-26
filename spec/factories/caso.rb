# Read about factories at https://github.com/thoughtbot/factory_girl

FactoryGirl.define do
  factory :caso do
    titulo "MyString"
    fecha "2013-12-23"
    hora "MyString"
    duracion "MyString"
    memo "MyText"
    grconfiabilidad "MyString"
    gresclarecimiento "MyString"
    grimpunidad "MyString"
    grinformacion "MyString"
    bienes "MyText"
    id_intervalo 1
  end
end
