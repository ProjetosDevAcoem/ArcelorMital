import mysql from 'mysql2';
import fetch from 'node-fetch';

// Configuração do MySQL
const connection = mysql.createConnection({
    host: '127.0.0.1',
    user: 'kunak_estacoes',
    password: 'Acoem@2024',
    database: 'kunak_estacoes',
    port: 3307,
});

// Função para duplicar valores no banco de dados
function duplicarUltimoValor(timestamp, AQI, PM10AVG1H, PM25AVG1H, O3GCcAVG1H, COGCcAVG1H, table) {
    const comando = `
    INSERT INTO ${table} (TimeStamp, Tag, Latitude, Longitude, AQI, PM10AVG1H, PM25AVG1H, O3GCcAVG1H, COGCcAVG1H)
    SELECT ?, Tag, Latitude, Longitude, ?, ?, ?, ?, ?
    FROM ${table}
    ORDER BY TimeStamp DESC
    LIMIT 1;
    `;

    connection.execute(comando, [timestamp, AQI, PM10AVG1H, PM25AVG1H, O3GCcAVG1H, COGCcAVG1H], (err, results) => {
        if (err) {
            console.error('Erro ao executar a consulta:', err);
            return;
        }
        console.log('Registro duplicado com sucesso:', results);
    });
}

// Configuração da API
const url_prefix = "https://kunakcloud.com/openAPIv0/v1/rest";
const username = "demokunak_city";
const password = "JDK3c8Y4qf";
const auth = Buffer.from(`${username}:${password}`).toString('base64');

// Cache das estações
const stationCache = new Map();

// Função para buscar um elemento específico
async function getElement(serialNumber, element) {
    const url = `${url_prefix}/devices/${serialNumber}/elements/${encodeURIComponent(element)}/info`;

    try {
        const response = await fetch(url, {
            method: "GET",
            headers: {
                "Authorization": `Basic ${auth}`,
                "Content-Type": "application/json",
            },
        });

        if (!response.ok) {
            throw new Error(`Erro HTTP! Status: ${response.status}`);
        }

        const data = await response.json();
        return data.last_read ? data.last_read.value : null;
    } catch (error) {
        console.error(`Erro ao obter ${element} da estação ${serialNumber}:`, error);
        return null;
    }
}

// Função principal
async function main() {
    const stations = [
        "4223410062", "4223410063", "4223410064", "4223410065", "4223410066",
        "4223410067", "4223410068", "4223410069", "4223410070", "4223410071"
    ];
    
    const dataPoints = []; // Array para armazenar os dados

    for (const serialNumber of stations) {
        const tablename = `S_${serialNumber}`;

        // Buscar valores individualmente
        const AQI = await getElement(serialNumber, "AQI");
        const PM10 = await getElement(serialNumber, "PM10 AVG1H");
        const PM25 = await getElement(serialNumber, "PM2.5 AVG1H");
        const O3 = await getElement(serialNumber, "O3 GCc AVG1H");
        const CO = await getElement(serialNumber, "CO GCc AVG1H");


        if (AQI !== null && PM10 !== null) {
            const timestamp = Date.now(); // Simulando timestamp, ajuste conforme necessário
            duplicarUltimoValor(timestamp, AQI, PM10, PM25, O3, CO, tablename);
            console.log(`Valor duplicado para ${tablename}: AQI=${AQI}, PM10=${PM10}, PM2.5=${PM25}, O3=${O3}`);
        } else {
            console.warn(`Dados incompletos para estação ${serialNumber}: AQI=${AQI}, PM10=${PM10}, CO${CO}`);
        }
    }

    connection.end();
}

// Executa o programa principal
main();
