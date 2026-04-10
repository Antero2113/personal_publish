В этой папке (htdocs), помимо папки api расположены все html, js, css файлы, обрабатывающие данные с бэка

В коде обращение к json, возвращаемому бэком, примерно так выглядит:
```
const getCategories = async () => 
{
	const response = await fetch('/api/', { method: "GET"});
	return await response.json();
}
```
