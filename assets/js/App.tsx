import React from "react";
import Layout from "./components/Layout";
import {Button, Card} from "react-bootstrap";

const App = () => {
    return (
        <Layout>
            <Card>
                <Card.Header>Courses</Card.Header>
                <Card.Body>
                    <Card.Title>Special title treatment</Card.Title>
                    <Card.Text>
                        With supporting text below as a natural lead-in to additional content.
                    </Card.Text>
                    <Button variant="primary">Go somewhere</Button>
                </Card.Body>
            </Card>
        </Layout>
    )
};

export default App;