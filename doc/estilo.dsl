<!DOCTYPE style-sheet PUBLIC "-//James Clark//DTD DSSSL Style Sheet//EN" [ <!ENTITY % html "IGNORE"> <![%html;[
<!ENTITY % print "INCLUDE">
<!ENTITY docbook.dsl PUBLIC "-//Norman Walsh//DOCUMENT DocBook HTML Stylesheet//EN" CDATA dsssl>
]]>
<!ENTITY % print "INCLUDE">
<![%print;[
<!ENTITY docbook.dsl PUBLIC "-//Norman Walsh//DOCUMENT DocBook Print Stylesheet//EN" CDATA dsssl>
]]>
<!ENTITY docbookrep-html.dsl SYSTEM "docbookrep_html.dsl">
<!ENTITY docbookrep-tex.dsl SYSTEM "docbookrep_tex.dsl">

]>
<!-- Detalles de estilo.   Cedido al dominio público. -->

<style-sheet>
<style-specification id="print" use="docbook">
<style-specification-body> 

;; printing:

<![%print;[

&docbookrep-tex.dsl;

(define bop-footnotes
  ;; Make "bottom-of-page" footnotes?
  #t)

(define tex-backend #t)

(define %graphic-default-extension%
  ;; Default extension for graphic FILEREFs
  "eps")

(define %chapter-autolabel%
  ;; Automatic enumeration of chapters
  #t)

(define %section-autolabel%
  ;; Automatic enumeration of sections
  #t)
]]>
</style-specification-body>
</style-specification>

<style-specification id="html" use="docbook">
<style-specification-body> 

;; HTML
<![%html;[

&docbookrep-html.dsl;

(define %use-id-as-filename%
  #t)

(define %citerefentry-link%
  #t)

(define %graphic-default-extension%
  ;; Default extension for graphic FILEREFs
  "png")

(define %chapter-autolabel%
  ;; Automatic enumeration of chapters
  #t)

(define %section-autolabel%
  ;; Automatic enumerations of sections
  #t)

(define %html-ext%
  ;; when producing HTML files, use this extension
  ".html")

(define %root-filename%
  "index")


]]>


</style-specification-body>
</style-specification>
<external-specification id="docbook" document="docbook.dsl">
</style-sheet>
