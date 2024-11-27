@echo off
echo Compilando y ejecutando el proyecto...

set CLASSPATH=.
for %%f in (lib\*.jar) do set CLASSPATH=!CLASSPATH!;%%f

javac -d target/classes src/main/java/com/novo/NovoApplication.java
java -cp target/classes com.novo.NovoApplication 