# covid-status-bing

Retorna as informações do novo corona vírus no Brasil buscando no [Bing](https://www.bing.com/covid/data "Bing"), salva as informações na raíz (data.json) cria um backup  (/old/data.json), antes de sobrescrever o (data.json) cria um arquivo com o nome do timestamp que foi atualizado em (/old/{timestamps}.json)!


### **Arquivo data.json**
- updated -> timestamp (Horário que foi atualizado),
- old -> timestamps (Atualizações salvas no data.txt para acesso em /old),
- data -> states ('sp', 'df', 'rj', etc...),
- data -> confirmed (Número de casos confirmados),
- data -> revored (Número de casos recuperados),
- data -> deaths (Número de casos fatais)

### **Pasta old**
- data.json -> (Possui um backup do data.json antes de atualizar),
- {timestamps}.json -> (Toda vez que atualizar será criado um arquivo com nome do timestamp da atualização, com as informações da atualiação antiga),

### **Aquivo data.txt**
- Possui o nome dos arquivos salvo em /old

### **Por que salvar as informações antiga?**
Com as informações antiga é possível fazer gráfico comparando a evolução do vírus!
