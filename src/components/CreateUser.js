import { useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";

function CreateUser() {
    const navigate = useNavigate();

    const [inputs, setInput] = useState({})

    const handleSubmit = (event) => {
        event.preventDefault();
        console.log(inputs);

        axios.post('http://localhost/reactCrud/react-crud/api/user/save', inputs).then(function(response){
            console.log(response.data);
            navigate('/')
        });
        
    }

    const handleChange  = (event) => {
        const name = event.target.name;
        const value = event.target.value;

        setInput(values => ({...values, [name]: value}))
    }

    return(
        <div className="form-container">
            <h1 className="form-header">Create User</h1>
            <form onSubmit={handleSubmit}>
                <label>First Name:</label>
                <input type="text" name="fname" className="form-inputs" onChange={handleChange}/>

                <br/>

                <label>Last Name:</label>
                <input type="text" name="lname" className="form-inputs" onChange={handleChange}/>
                 
                <br/>

                <label>Mobile Number:</label>
                <input type="text" name="mobilenum" className="form-inputs" onChange={handleChange}/>

                <br/>

                <input type="submit"></input>
            </form>
        </div>
    );
}

export default CreateUser