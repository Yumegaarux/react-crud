import { data, useNavigate } from "react-router-dom";
import { useState, useEffect} from "react";
import { useParams } from "react-router-dom";
import axios from "axios";

function EditUser() {
    // uses the parameter passed to EditUser on routing.
    const { id } = useParams();
    const navigate = useNavigate();

    const [inputs, setInput] = useState({})
    const [userData, setUserData] = useState({
        fname: "",
        lname: "",
        mobileNum: ""
    });
    const [isChanged, setIsChanged] = useState();

    useEffect(() => {
        if (id) getUserData();
    }, [id]);

    function getUserData() {
        axios.get(`http://localhost/reactCrud/react-crud/backend/api/public/user/${id}`)
            .then(function(response){
                setUserData(response.data);
            })
            .catch(function(error){
                console.log("", error);
                setUserData([]);
            });
    }

    const handleSubmit = (event) => {
        event.preventDefault();
        console.log(inputs);

        axios.put(`http://localhost/reactCrud/react-crud/backend/api/public/user/${id}`, inputs).then(function(response){
            console.log(response.data);
            navigate('/');
        });
        
    }

    const handleChange  = (event) => {
        const { name, value } = event.target;

        // copies all key-value pairs from prev into a new object.
        // ex. prev = { fname: "Juan", lname: "Dela Cruz" }
        setUserData(prev => ({
            ...prev,
            [name]: value
        }));
        const changed = value !== userData[name];
        setIsChanged(changed);
    }

    return(
        <div className="form-container">
            <h1 className="form-header">Edit User</h1>
            <form onSubmit={handleSubmit}>
                <label>First Name:</label>
                <input type="text" name="fname" value={userData.fname} className="form-inputs" onChange={handleChange}/>

                <br/>

                <label>Last Name:</label>
                <input type="text" name="lname" value={userData.lname} className="form-inputs" onChange={handleChange}/>
                
                <br/>

                <label>Mobile Number:</label>
                <input type="text" name="mobilenum" value={userData.mobilenum} className="form-inputs" onChange={handleChange}/>

                <br/>
 
                <input type="submit" disabled={!isChanged}></input>
            </form>
        </div>
    );
}

export default EditUser