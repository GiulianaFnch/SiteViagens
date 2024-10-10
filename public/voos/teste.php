<html>

<head>
    <title>
        Flight Search Results
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            width: 90%;
            margin: 20px auto;
        }

        .header,
        .filters,
        .flight-results,
        .other-flights {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header select,
        .header input {
            margin-right: 10px;
        }

        .filters {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filters button {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            cursor: pointer;
        }

        .filters button:hover {
            background-color: #f1f1f1;
        }

        .flight-results h2,
        .other-flights h2 {
            margin-top: 0;
        }

        .flight-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .flight-item .details {
            display: flex;
            align-items: center;
        }

        .flight-item .details img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .flight-item .details .info {
            margin-right: 20px;
        }

        .flight-item .details .info p {
            margin: 5px 0;
        }

        .flight-item .details .info .co2 {
            color: green;
        }

        .flight-item .price {
            font-size: 18px;
            font-weight: bold;
        }

        .flight-item .price span {
            color: #888;
            font-size: 14px;
        }

        .flight-item .price .high {
            color: red;
        }

        .flight-item .price .low {
            color: green;
        }

        .other-flights .flight-item {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div>
                <select>
                    <option>
                        Round trip
                    </option>
                </select>
                <select>
                    <option>
                        1
                    </option>
                </select>
                <select>
                    <option>
                        Economy
                    </option>
                </select>
            </div>
            <div>
                <input placeholder="Porto" type="text" />
                <i class="fas fa-exchange-alt">
                </i>
                <input placeholder="Amsterdam AMS" type="text" />
                <input type="date" value="2023-10-25" />
                <input type="date" value="2023-10-27" />
            </div>
        </div>
        <div class="filters">
            <div>
                <button>
                    All filters
                </button>
                <button>
                    Stops
                </button>
                <button>
                    Airlines
                </button>
                <button>
                    Bags
                </button>
                <button>
                    Price
                </button>
                <button>
                    Times
                </button>
                <button>
                    Emissions
                </button>
                <button>
                    Connecting airports
                </button>
                <button>
                    Duration
                </button>
            </div>
            <div>
                <button>
                    Track prices
                </button>
                <button>
                    Date grid
                </button>
                <button>
                    Price graph
                </button>
            </div>
        </div>

        
        <div class="flight-results">
            <h2>
                Best departing flights
            </h2>
            <p>
                Ranked based on price and convenience
            </p>
            <div class="flight-item">
                <div class="details">
                    <img alt="Airline logo" height="50"
                        src="https://storage.googleapis.com/a1aa/image/y1UAnxkP7TqIJ9tNbeJUMIDuzPVXYwAG46pBXvwwsflVsblTA.jpg"
                        width="50" />
                    <div class="info">
                        <p>
                            7:20 PM - 9:00 AM
                        </p>
                        <p>
                            12 hr 40 min
                        </p>
                        <p>
                            1 stop
                        </p>
                        <p>
                            224 kg CO2e
                        </p>
                    </div>
                </div>
                <div class="price">
                    €275
                    <span>
                        round trip
                    </span>
                </div>
            </div>
            <div class="flight-item">
                <div class="details">
                    <img alt="Airline logo" height="50"
                        src="https://storage.googleapis.com/a1aa/image/y1UAnxkP7TqIJ9tNbeJUMIDuzPVXYwAG46pBXvwwsflVsblTA.jpg"
                        width="50" />
                    <div class="info">
                        <p>
                            6:30 AM - 10:00 AM
                        </p>
                        <p>
                            2 hr 30 min
                        </p>
                        <p>
                            Nonstop
                        </p>
                        <p class="co2">
                            Avoids as much CO2e as 3,774 trees absorb in a day
                        </p>
                    </div>
                </div>
                <div class="price">
                    €357
                    <span>
                        round trip
                    </span>
                </div>
            </div>
            <div class="flight-item">
                <div class="details">
                    <img alt="Airline logo" height="50"
                        src="https://storage.googleapis.com/a1aa/image/y1UAnxkP7TqIJ9tNbeJUMIDuzPVXYwAG46pBXvwwsflVsblTA.jpg"
                        width="50" />
                    <div class="info">
                        <p>
                            11:25 AM - 3:05 PM
                        </p>
                        <p>
                            2 hr 40 min
                        </p>
                        <p>
                            Nonstop
                        </p>
                        <p>
                            142 kg CO2e
                        </p>
                    </div>
                </div>
                <div class="price">
                    €385
                    <span>
                        round trip
                    </span>
                </div>
            </div>
        </div>
        <div class="other-flights">
            <h2>
                Other departing flights
            </h2>
            <div class="flight-item">
                <div class="details">
                    <img alt="Airline logo" height="50"
                        src="https://storage.googleapis.com/a1aa/image/y1UAnxkP7TqIJ9tNbeJUMIDuzPVXYwAG46pBXvwwsflVsblTA.jpg"
                        width="50" />
                    <div class="info">
                        <p>
                            8:40 AM - 10:15 PM
                        </p>
                        <p>
                            12 hr 35 min
                        </p>
                        <p>
                            1 stop
                        </p>
                        <p>
                            228 kg CO2e
                        </p>
                    </div>
                </div>
                <div class="price">
                    €275
                    <span>
                        round trip
                    </span>
                </div>
            </div>
            <div class="flight-item">
                <div class="details">
                    <img alt="Airline logo" height="50"
                        src="https://storage.googleapis.com/a1aa/image/y1UAnxkP7TqIJ9tNbeJUMIDuzPVXYwAG46pBXvwwsflVsblTA.jpg"
                        width="50" />
                    <div class="info">
                        <p>
                            6:00 PM - 8:05 AM
                        </p>
                        <p>
                            13 hr 5 min
                        </p>
                        <p>
                            1 stop
                        </p>
                        <p>
                            219 kg CO2e
                        </p>
                    </div>
                </div>
                <div class="price">
                    €347
                    <span>
                        round trip
                    </span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>